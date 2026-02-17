<?php

namespace Apiura\Http\Controllers;

use Apiura\Services\OpenApiExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApiuraController extends Controller
{
    public function index(): View
    {
        return view('apiura::apiura', [
            'spec' => $this->getSpec(),
            'telescopeEnabled' => class_exists(\Laravel\Telescope\Telescope::class),
        ]);
    }

    private function getSpec(): array
    {
        // If Scramble is installed, generate spec live (always up-to-date)
        if (class_exists(\Dedoc\Scramble\Generator::class)) {
            try {
                $spec = app(\Dedoc\Scramble\Generator::class)();
                if (is_array($spec)) {
                    return $spec;
                }
            } catch (\Throwable $e) {
                // Fall through to static file
            }
        }

        // Fall back to static spec file
        $specPath = config('apiura.spec_path', base_path('api.json'));
        if (file_exists($specPath)) {
            return json_decode(file_get_contents($specPath), true) ?? [];
        }

        return [];
    }

    public function telescopeEntries(Request $request): JsonResponse
    {
        if (! class_exists(\Laravel\Telescope\Telescope::class)) {
            return response()->json(['message' => 'Telescope is not installed.'], 404);
        }

        $perPage = min((int) $request->get('per_page', 30), 100);
        $page = max((int) $request->get('page', 1), 1);
        $search = $request->get('search', '');
        $methodFilter = strtoupper($request->get('method', ''));

        $query = DB::table('telescope_entries')
            ->where('type', 'request')
            ->orderByDesc('sequence');

        // Filter to only /api/* routes
        $query->where('content', 'like', '%/api/%');

        if ($search) {
            $query->where('content', 'like', '%' . addcslashes($search, '%_') . '%');
        }

        if ($methodFilter && in_array($methodFilter, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            $query->where('content', 'like', '%"method":"' . $methodFilter . '"%');
        }

        $total = $query->count();
        $entries = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

        $items = $entries->map(function ($entry) {
            $content = json_decode($entry->content, true) ?? [];

            return [
                'uuid' => $entry->uuid,
                'method' => $content['method'] ?? 'GET',
                'uri' => $content['uri'] ?? '',
                'status' => $content['response_status'] ?? 0,
                'duration' => $content['duration'] ?? 0,
                'timestamp' => $entry->created_at,
            ];
        });

        return response()->json([
            'entries' => $items->values(),
            'has_more' => ($page * $perPage) < $total,
            'total' => $total,
            'page' => $page,
        ]);
    }

    public function telescopeEntry(string $uuid): JsonResponse
    {
        if (! class_exists(\Laravel\Telescope\Telescope::class)) {
            return response()->json(['message' => 'Telescope is not installed.'], 404);
        }

        $entry = DB::table('telescope_entries')
            ->where('uuid', $uuid)
            ->where('type', 'request')
            ->first();

        if (! $entry) {
            return response()->json(['message' => 'Entry not found.'], 404);
        }

        $content = json_decode($entry->content, true) ?? [];

        return response()->json([
            'uuid' => $entry->uuid,
            'method' => $content['method'] ?? 'GET',
            'uri' => $content['uri'] ?? '',
            'status' => $content['response_status'] ?? 0,
            'duration' => $content['duration'] ?? 0,
            'timestamp' => $entry->created_at,
            'request_headers' => $content['headers'] ?? [],
            'payload' => $content['payload'] ?? [],
            'query' => $this->parseQueryFromUri($content['uri'] ?? ''),
            'response_status' => $content['response_status'] ?? 0,
            'response_headers' => $content['response_headers'] ?? [],
            'response_body' => $content['response'] ?? null,
        ]);
    }

    private function parseQueryFromUri(string $uri): array
    {
        $parts = parse_url($uri);
        $query = [];
        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
        }

        return $query;
    }

    public function exportOpenApi(string $mode, OpenApiExportService $service): JsonResponse
    {
        $spec = $this->getSpec();
        if (empty($spec)) {
            return response()->json(['message' => 'OpenAPI spec not available.'], 404);
        }

        $result = $service->export($spec, $mode);

        return response()->json($result)
            ->header('Content-Disposition', "attachment; filename=\"openapi-{$mode}.json\"");
    }

    public function exportMarkdown(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $zipPath = tempnam(sys_get_temp_dir(), 'api_docs_') . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE);

        $spec = $this->getSpec();
        if (! empty($spec)) {
            $byTag = $this->groupEndpointsByTag($spec);

            foreach ($byTag as $tag => $endpoints) {
                $tagMd = $this->generateTagMarkdown($tag, $endpoints, $spec);
                $filename = Str::slug($tag) . '.md';
                $zip->addFromString("calls/{$filename}", $tagMd);
            }
        }

        $dbMd = $this->generateDatabaseMarkdown();
        $zip->addFromString('databases.md', $dbMd);

        $zip->close();

        return response()->download($zipPath, 'api-documentation.zip')->deleteFileAfterSend(true);
    }

    private function groupEndpointsByTag(array $spec): array
    {
        $byTag = [];
        foreach ($spec['paths'] ?? [] as $path => $methods) {
            foreach ($methods as $method => $details) {
                if (in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
                    $tags = $details['tags'] ?? ['Untagged'];
                    foreach ($tags as $tag) {
                        $byTag[$tag][] = [
                            'method' => strtoupper($method),
                            'path' => $path,
                            'summary' => $details['summary'] ?? '',
                            'description' => $details['description'] ?? '',
                            'parameters' => $details['parameters'] ?? [],
                            'requestBody' => $details['requestBody'] ?? null,
                            'responses' => $details['responses'] ?? [],
                        ];
                    }
                }
            }
        }

        return $byTag;
    }

    private function generateTagMarkdown(string $tag, array $endpoints, array $spec): string
    {
        $md = "# {$tag}\n\n";

        if (! empty($spec['servers'])) {
            $md .= "**Base URL:** `" . ($spec['servers'][0]['url'] ?? '') . "`\n\n";
        }

        foreach ($endpoints as $ep) {
            $md .= "## `{$ep['method']}` {$ep['path']}\n\n";
            if ($ep['summary']) {
                $md .= "{$ep['summary']}\n\n";
            }
            if ($ep['description']) {
                $md .= "{$ep['description']}\n\n";
            }

            if (! empty($ep['parameters'])) {
                $md .= "**Parameters:**\n\n";
                $md .= "| Name | In | Type | Required | Description |\n";
                $md .= "|------|----|------|----------|-------------|\n";
                foreach ($ep['parameters'] as $param) {
                    $type = $param['schema']['type'] ?? '-';
                    $required = ! empty($param['required']) ? 'Yes' : 'No';
                    $desc = $param['description'] ?? '-';
                    $md .= "| {$param['name']} | {$param['in']} | {$type} | {$required} | {$desc} |\n";
                }
                $md .= "\n";
            }

            if ($ep['requestBody']) {
                $content = $ep['requestBody']['content']['application/json'] ?? null;
                if ($content && isset($content['schema'])) {
                    $md .= "**Request Body:**\n\n";
                    $md .= "```json\n" . json_encode($content['schema'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n```\n\n";
                }
            }

            if (! empty($ep['responses'])) {
                $md .= "**Responses:**\n\n";
                foreach ($ep['responses'] as $code => $resp) {
                    $md .= "- `{$code}`: " . ($resp['description'] ?? '-') . "\n";
                }
                $md .= "\n";
            }

            $md .= "---\n\n";
        }

        return $md;
    }

    private function getProjectTables(): array
    {
        $database = DB::connection()->getDatabaseName();
        $prefix = $database . '.';

        $allTables = collect(Schema::getTableListing())
            ->filter(fn ($t) => str_starts_with($t, $prefix) || ! str_contains($t, '.'))
            ->map(fn ($t) => str_starts_with($t, $prefix) ? substr($t, strlen($prefix)) : $t)
            ->values()
            ->all();

        $excludes = config('apiura.schema_exclude_tables', []);

        return array_values(array_filter($allTables, function ($table) use ($excludes) {
            foreach ($excludes as $pattern) {
                $pattern = trim($pattern);
                if ($pattern === '') continue;
                if (str_ends_with($pattern, '*')) {
                    if (str_starts_with($table, rtrim($pattern, '*'))) return false;
                } elseif ($table === $pattern) {
                    return false;
                }
            }
            return true;
        }));
    }

    private function generateDatabaseMarkdown(): string
    {
        $tables = $this->getProjectTables();
        $md = "# Database Schema\n\n";
        $md .= "Generated: " . now()->toDateTimeString() . "\n\n";

        foreach ($tables as $table) {
            $columns = Schema::getColumns($table);
            $indexes = Schema::getIndexes($table);
            $foreignKeys = Schema::getForeignKeys($table);

            $md .= "## `{$table}`\n\n";

            $md .= "| Column | Type | Nullable | Default |\n";
            $md .= "|--------|------|----------|---------|\n";
            foreach ($columns as $col) {
                $nullable = ($col['nullable'] ?? false) ? 'Yes' : 'No';
                $default = $col['default'] ?? '-';
                $type = $col['type_name'] ?? $col['type'] ?? '-';
                $name = $col['name'] ?? '-';
                $md .= "| {$name} | {$type} | {$nullable} | {$default} |\n";
            }
            $md .= "\n";

            if (! empty($indexes)) {
                $md .= "**Indexes:**\n\n";
                foreach ($indexes as $idx) {
                    $cols = implode(', ', $idx['columns'] ?? []);
                    $unique = ! empty($idx['unique']) ? ' (unique)' : '';
                    $primary = ! empty($idx['primary']) ? ' (primary)' : '';
                    $md .= "- `{$idx['name']}`: [{$cols}]{$unique}{$primary}\n";
                }
                $md .= "\n";
            }

            if (! empty($foreignKeys)) {
                $md .= "**Foreign Keys:**\n\n";
                foreach ($foreignKeys as $fk) {
                    $localCols = implode(', ', $fk['columns'] ?? []);
                    $foreignCols = implode(', ', $fk['foreign_columns'] ?? []);
                    $foreignTable = $fk['foreign_table'] ?? '-';
                    $md .= "- `{$localCols}` -> `{$foreignTable}`.`{$foreignCols}`\n";
                }
                $md .= "\n";
            }
        }

        return $md;
    }

    public function dbSchema(): JsonResponse
    {
        $tables = $this->getProjectTables();
        $schema = [];

        foreach ($tables as $table) {
            $columns = Schema::getColumns($table);
            $indexes = Schema::getIndexes($table);
            $foreignKeys = Schema::getForeignKeys($table);

            $schema[$table] = [
                'columns' => $columns,
                'indexes' => $indexes,
                'foreign_keys' => $foreignKeys,
            ];
        }

        return response()->json($schema);
    }
}
