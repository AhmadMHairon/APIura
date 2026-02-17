<?php

namespace Apiura\Console\Commands;

use Apiura\Services\OpenApiExportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GenerateApiDocs extends Command
{
    protected $signature = 'docs:generate
                            {--output=.claude/backend-calls : Output directory path}
                            {--skip-schema : Skip database schema generation}
                            {--apidog : Also generate OpenAPI 3.0 spec for Apidog import}
                            {--no-examples : Skip adding request body examples (use for re-imports to preserve Apidog examples)}
                            {--export-openapi= : Export OpenAPI JSON: with-cases, with-examples, or clean}';

    protected $description = 'Generate API documentation markdown from Scramble OpenAPI spec';

    protected array $openapi = [];

    protected array $allowedTables = [];

    protected array $publicRoutes = [];

    protected string $baseUrl = '/api';

    /** @var array<string, array<string, array>> Route -> query params discovered from controllers */
    protected array $discoveredQueryParams = [];

    /** @var array<string, array<string>> Field name -> enum values from DB + validation rules */
    protected array $discoveredEnums = [];

    public function handle(): int
    {
        $this->info('Generating API documentation...');

        // Step 1: Auto-detect tables from Models
        $this->allowedTables = $this->discoverTablesFromModels();
        $this->info('Discovered '.count($this->allowedTables).' tables from models');

        // Step 2: Auto-detect public routes (no auth middleware)
        $this->publicRoutes = $this->discoverPublicRoutes();
        $this->info('Discovered '.count($this->publicRoutes).' public routes');

        // Step 3: Auto-detect base URL
        $this->baseUrl = $this->discoverBaseUrl();

        // Step 3b: Discover enum values from database and validation rules
        $this->discoveredEnums = $this->discoverEnumValues();
        $this->info('Discovered enums for '.count($this->discoveredEnums).' fields');

        // Step 3c: Discover query parameters from controllers
        $this->discoveredQueryParams = $this->discoverQueryParams();
        $this->info('Discovered query params for '.count($this->discoveredQueryParams).' routes');

        // Step 4: Export Scramble OpenAPI spec
        $this->call('scramble:export', ['--path' => 'api.json']);

        $apiJsonPath = base_path('api.json');
        if (! File::exists($apiJsonPath)) {
            $this->error('Failed to generate OpenAPI spec');

            return 1;
        }

        $this->openapi = json_decode(File::get($apiJsonPath), true);

        // Step 4b: Convert to OpenAPI 3.0 for Apidog compatibility
        if ($this->option('apidog')) {
            $converted = $this->convertToOpenApi30($this->openapi);
            $apidogPath = base_path('api-apidog.json');
            File::put($apidogPath, json_encode($converted, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Generated api-apidog.json (OpenAPI 3.0 for Apidog import)');
        }

        // Step 4c: Export OpenAPI with mode variant
        if ($exportMode = $this->option('export-openapi')) {
            $apidogPath = base_path('api-apidog.json');
            if (! File::exists($apidogPath)) {
                $this->error('api-apidog.json not found. Run with --apidog first.');

                return 1;
            }

            $apidogSpec = json_decode(File::get($apidogPath), true);
            $service = app(OpenApiExportService::class);
            $exported = $service->export($apidogSpec, $exportMode);

            $outputDir = base_path($this->option('output'));
            File::ensureDirectoryExists($outputDir);
            $exportPath = $outputDir . "/openapi-{$exportMode}.json";
            File::put($exportPath, json_encode($exported, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info("Exported OpenAPI spec ({$exportMode}) to {$exportPath}");
        }

        // Step 5: Prepare output directory
        $outputPath = base_path($this->option('output'));
        $callsPath = $outputPath.'/calls';

        if (File::isDirectory($outputPath)) {
            File::cleanDirectory($outputPath);
        }

        File::ensureDirectoryExists($callsPath);

        // Step 6: Generate database schema file
        if (! $this->option('skip-schema')) {
            $this->writeDatabaseFile($outputPath);
            $this->info('Generated databases.md');
        }

        // Step 7: Generate endpoint files per tag
        $this->writeEndpointFiles($callsPath);

        $this->info("Documentation generated at: {$outputPath}");

        return 0;
    }

    protected function writeDatabaseFile(string $outputPath): void
    {
        $md = "# Database Schema\n\n";

        foreach ($this->allowedTables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            $md .= "## {$tableName}\n";
            $md .= "| Column | Type | Description |\n";
            $md .= "|--------|------|-------------|\n";

            $columns = Schema::getColumns($tableName);
            foreach ($columns as $column) {
                $name = $column['name'];
                $type = $column['type'];
                $nullable = $column['nullable'] ? ' (nullable)' : '';
                $description = $this->guessColumnDescription($name, $type);

                $md .= "| {$name} | {$type} | {$description}{$nullable} |\n";
            }

            $md .= "\n";
        }

        // Notes section
        $md .= "---\n\n";
        $md .= "## Notes\n\n";

        $decimalColumns = $this->discoverDecimalPrecision();
        if (! empty($decimalColumns)) {
            $md .= "### Decimal Precision\n";
            foreach ($decimalColumns as $info) {
                $md .= "- `{$info['table']}.{$info['column']}`: `{$info['type']}`\n";
            }
            $md .= "\n";
        }

        $polymorphicColumns = $this->discoverPolymorphicRelationships();
        if (! empty($polymorphicColumns)) {
            $md .= "### Polymorphic Relationships\n";
            foreach ($polymorphicColumns as $info) {
                $md .= "- `{$info['table']}`: `{$info['type_column']}` + `{$info['id_column']}`\n";
            }
            $md .= "\n";
        }

        $md .= "---\n\n";
        $md .= '**Last Updated:** '.now()->format('Y-m-d H:i:s')."\n";

        File::put($outputPath.'/databases.md', $md);
    }

    protected function writeEndpointFiles(string $callsPath): void
    {
        $groupedPaths = [];
        foreach ($this->openapi['paths'] ?? [] as $path => $methods) {
            foreach ($methods as $method => $operation) {
                if (! is_array($operation)) {
                    continue;
                }

                $tag = $operation['tags'][0] ?? 'Other';
                $groupedPaths[$tag][] = [
                    'path' => $path,
                    'method' => strtoupper($method),
                    'operation' => $operation,
                ];
            }
        }

        uksort($groupedPaths, function ($a, $b) {
            if ($a === 'Auth') {
                return -1;
            }
            if ($b === 'Auth') {
                return 1;
            }

            return strcmp($a, $b);
        });

        foreach ($groupedPaths as $tag => $endpoints) {
            $md = "# {$tag}\n\n";
            $md .= "**Base URL:** `{$this->baseUrl}`\n\n";
            $md .= "---\n\n";

            foreach ($endpoints as $endpoint) {
                $md .= $this->buildEndpointMarkdown($endpoint['path'], $endpoint['method'], $endpoint['operation']);
            }

            $md .= '**Last Updated:** '.now()->format('Y-m-d H:i:s')."\n";

            $fileName = Str::kebab($tag).'-backend-calls.md';
            File::put($callsPath.'/'.$fileName, $md);
            $this->info("Generated calls/{$fileName}");
        }
    }

    protected function buildEndpointMarkdown(string $path, string $method, array $operation): string
    {
        $fullPath = '/api'.$path;
        $summary = $operation['summary'] ?? '';
        $md = '';

        $md .= "## {$method} {$fullPath}\n";

        if ($summary) {
            $md .= "**Description:** {$summary}\n";
        }

        $isPublic = $this->isPublicRoute($method, $path);
        $md .= '**Auth:** '.($isPublic ? 'Public' : 'Required')."\n";

        // Query Parameters - merge from spec and discovered
        $queryParams = array_filter($operation['parameters'] ?? [], fn ($p) => ($p['in'] ?? '') === 'query');
        $discoveredKey = $method.' '.$fullPath;
        $discoveredParams = $this->discoveredQueryParams[$discoveredKey] ?? [];

        // Merge discovered params (avoid duplicates)
        $existingNames = array_column($queryParams, 'name');
        foreach ($discoveredParams as $discovered) {
            if (! in_array($discovered['name'], $existingNames)) {
                $queryParams[] = [
                    'name' => $discovered['name'],
                    'required' => $discovered['required'],
                    'schema' => ['type' => $discovered['type']],
                ];
            }
        }

        if (! empty($queryParams)) {
            $md .= "\n**Query Parameters:**\n";
            foreach ($queryParams as $param) {
                $required = ($param['required'] ?? false) ? ' (required)' : ' (optional)';
                $type = $param['schema']['type'] ?? $param['type'] ?? 'string';
                $description = $param['description'] ?? '';

                // Add enum options if available
                $enumValues = $this->getEnumForField($param['name']);
                if ($enumValues && empty($description)) {
                    $description = 'Options: `'.implode('`, `', $enumValues).'`';
                }

                $md .= "- `{$param['name']}` ({$type}){$required}";
                if ($description) {
                    $md .= " - {$description}";
                }
                $md .= "\n";
            }
        }

        // Request body
        if (! empty($operation['requestBody'])) {
            $schema = $operation['requestBody']['content']['application/json']['schema'] ?? [];
            $fieldDescriptions = $this->extractFieldDescriptions($schema);

            $md .= "\n**Request Body:**\n";
            $md .= "```json\n";
            $example = $this->extractRequestSchema($operation['requestBody']);
            $md .= json_encode($example, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $md .= "\n```\n";

            // Add field descriptions with enum options
            if (! empty($fieldDescriptions)) {
                $md .= "\n**Field Options:**\n";
                foreach ($fieldDescriptions as $field => $desc) {
                    $md .= "- `{$field}`: {$desc}\n";
                }
            }
        }

        // Response
        $responseGenerated = false;
        foreach ($operation['responses'] ?? [] as $code => $response) {
            if ($code === '200' || $code === '201') {
                $schema = $this->extractResponseSchema($response);
                if ($this->hasContent($schema)) {
                    $md .= "\n**Response ({$code}):**\n";
                    $md .= "```json\n";
                    $md .= json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    $md .= "\n```\n";
                    $responseGenerated = true;
                    break;
                }
            }
        }

        if (! $responseGenerated) {
            foreach ($operation['responses'] ?? [] as $code => $response) {
                if ($code !== '401' && $code !== '422') {
                    $schema = $this->extractResponseSchema($response);
                    if ($this->hasContent($schema)) {
                        $md .= "\n**Response ({$code}):**\n";
                        $md .= "```json\n";
                        $md .= json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                        $md .= "\n```\n";
                        break;
                    }
                }
            }
        }

        $md .= "\n---\n\n";

        return $md;
    }

    /**
     * Auto-discover database tables from Eloquent models.
     */
    protected function discoverTablesFromModels(): array
    {
        $tables = [];
        $modelsPath = app_path('Models');

        if (! is_dir($modelsPath)) {
            return $tables;
        }

        $modelFiles = glob($modelsPath.'/*.php');

        foreach ($modelFiles as $file) {
            $className = 'App\\Models\\'.basename($file, '.php');

            if (! class_exists($className)) {
                continue;
            }

            try {
                $reflection = new \ReflectionClass($className);

                if ($reflection->isAbstract() || $reflection->isInterface()) {
                    continue;
                }

                if (! $reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class)) {
                    continue;
                }

                $model = new $className;
                $tableName = $model->getTable();

                if ($tableName && ! in_array($tableName, $tables)) {
                    $tables[] = $tableName;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        sort($tables);

        return $tables;
    }

    /**
     * Auto-discover public routes (routes without auth middleware).
     */
    protected function discoverPublicRoutes(): array
    {
        $publicRoutes = [];

        foreach (Route::getRoutes() as $route) {
            $uri = '/'.ltrim($route->uri(), '/');

            $middleware = $route->middleware();
            $hasAuth = false;

            foreach ($middleware as $m) {
                if (str_contains($m, 'auth') || str_contains($m, 'sanctum')) {
                    $hasAuth = true;
                    break;
                }
            }

            if (! $hasAuth && str_starts_with($uri, '/api')) {
                foreach ($route->methods() as $method) {
                    if ($method !== 'HEAD') {
                        $publicRoutes[] = [
                            'method' => $method,
                            'uri' => $uri,
                        ];
                    }
                }
            }
        }

        return $publicRoutes;
    }

    /**
     * Auto-detect base URL from API routes.
     */
    protected function discoverBaseUrl(): string
    {
        foreach (Route::getRoutes() as $route) {
            $uri = '/'.ltrim($route->uri(), '/');

            if (str_starts_with($uri, '/api/')) {
                if (preg_match('#^(/api/v\d+)#', $uri, $matches)) {
                    return $matches[1];
                }

                return '/api';
            }
        }

        return '/api';
    }

    /**
     * Check if a route is public (no auth required).
     */
    protected function isPublicRoute(string $method, string $path): bool
    {
        $fullPath = '/api'.$path;

        foreach ($this->publicRoutes as $route) {
            if ($route['method'] === $method && $route['uri'] === $fullPath) {
                return true;
            }
        }

        return false;
    }

    protected function guessColumnDescription(string $name, string $type): string
    {
        $descriptions = [
            'id' => 'Primary key',
            'uuid' => 'Unique identifier',
            'created_at' => 'Creation timestamp',
            'updated_at' => 'Update timestamp',
            'deleted_at' => 'Soft delete timestamp',
            'email' => 'Email address',
            'password' => 'Hashed password',
            'remember_token' => 'Remember token',
            'email_verified_at' => 'Email verification timestamp',
        ];

        if (isset($descriptions[$name])) {
            return $descriptions[$name];
        }

        if (Str::endsWith($name, '_id')) {
            $relation = Str::beforeLast($name, '_id');
            $table = Str::plural($relation);

            return "FK to {$table}";
        }

        if (Str::endsWith($name, '_type')) {
            return 'Polymorphic type';
        }

        if (Str::startsWith($name, 'is_') || Str::startsWith($name, 'has_') || Str::startsWith($name, 'can_')) {
            $label = Str::after($name, 'is_') ?: Str::after($name, 'has_') ?: Str::after($name, 'can_');

            return Str::headline($label).' flag';
        }

        if (Str::endsWith($name, '_at') || Str::endsWith($name, '_date')) {
            return Str::headline(Str::beforeLast($name, '_at') ?: Str::beforeLast($name, '_date'));
        }

        return Str::headline($name);
    }

    protected function extractRequestSchema(array $requestBody): mixed
    {
        $content = $requestBody['content']['application/json']['schema'] ?? [];

        return $this->schemaToExample($content);
    }

    /**
     * Extract field descriptions for fields that have enums or special types.
     */
    protected function extractFieldDescriptions(array $schema, string $prefix = ''): array
    {
        $descriptions = [];

        if (isset($schema['$ref'])) {
            $refPath = str_replace('#/components/schemas/', '', $schema['$ref']);
            $schema = $this->openapi['components']['schemas'][$refPath] ?? $schema;
        }

        foreach ($schema['properties'] ?? [] as $name => $prop) {
            if (! is_array($prop)) {
                continue;
            }

            $fieldKey = $prefix ? "{$prefix}.{$name}" : $name;

            // Check for enum in schema or discovered
            $enumValues = $prop['enum'] ?? $this->getEnumForField($name);
            if ($enumValues) {
                $descriptions[$fieldKey] = implode(' | ', array_map(fn ($v) => "`{$v}`", $enumValues));
            }

            // Recurse into nested objects
            if (($prop['type'] ?? '') === 'object') {
                $nested = $this->extractFieldDescriptions($prop, $fieldKey);
                $descriptions = array_merge($descriptions, $nested);
            }
        }

        return $descriptions;
    }

    protected function extractResponseSchema(array $response): mixed
    {
        if (isset($response['$ref'])) {
            $refPath = str_replace('#/components/responses/', '', $response['$ref']);
            $response = $this->openapi['components']['responses'][$refPath] ?? $response;
        }

        $content = $response['content']['application/json']['schema'] ?? [];

        return $this->schemaToExample($content);
    }

    protected function hasContent(mixed $value): bool
    {
        if (is_array($value)) {
            return ! empty($value);
        }

        return $value !== null && $value !== '';
    }

    protected function schemaToExample(array|string $schema): mixed
    {
        if (is_string($schema)) {
            return $schema;
        }

        if (empty($schema)) {
            return [];
        }

        if (isset($schema['$ref'])) {
            $refPath = str_replace('#/components/schemas/', '', $schema['$ref']);
            $schema = $this->openapi['components']['schemas'][$refPath] ?? $schema;
        }

        if (isset($schema['enum'])) {
            return $schema['enum'][0];
        }

        $type = $schema['type'] ?? 'object';

        if (is_array($type)) {
            $type = $type[0] ?? 'string';
        }

        switch ($type) {
            case 'object':
                $result = [];
                foreach ($schema['properties'] ?? [] as $name => $propSchema) {
                    $result[$name] = $this->schemaToExample($propSchema);
                }

                return $result;

            case 'array':
                $items = $schema['items'] ?? [];
                if (empty($items)) {
                    return [];
                }

                return [$this->schemaToExample($items)];

            case 'string':
                if (isset($schema['format'])) {
                    return match ($schema['format']) {
                        'date-time' => now()->toIso8601String(),
                        'date' => now()->toDateString(),
                        'email' => 'user@example.com',
                        'uri', 'url' => 'https://example.com',
                        default => 'string',
                    };
                }

                return 'string';

            case 'integer':
                return 1;

            case 'number':
                return 0.00;

            case 'boolean':
                return false;

            default:
                return null;
        }
    }

    /**
     * Discover decimal columns and their precision.
     */
    protected function discoverDecimalPrecision(): array
    {
        $decimals = [];

        foreach ($this->allowedTables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            $columns = Schema::getColumns($tableName);
            foreach ($columns as $column) {
                if (str_starts_with($column['type'], 'decimal')) {
                    $decimals[] = [
                        'table' => $tableName,
                        'column' => $column['name'],
                        'type' => $column['type'],
                    ];
                }
            }
        }

        return $decimals;
    }

    /**
     * Discover polymorphic relationships from column patterns.
     */
    protected function discoverPolymorphicRelationships(): array
    {
        $polymorphic = [];

        foreach ($this->allowedTables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            $columns = Schema::getColumns($tableName);
            $columnNames = array_column($columns, 'name');

            foreach ($columnNames as $name) {
                if (Str::endsWith($name, '_type')) {
                    $base = Str::beforeLast($name, '_type');
                    $idColumn = $base.'_id';

                    if (in_array($idColumn, $columnNames)) {
                        $polymorphic[] = [
                            'table' => $tableName,
                            'type_column' => $name,
                            'id_column' => $idColumn,
                        ];
                    }
                }
            }
        }

        return $polymorphic;
    }

    /**
     * Discover enum values from MySQL ENUM columns and validation rules in controllers.
     * Validation rules take precedence as they represent what the API actually accepts.
     */
    protected function discoverEnumValues(): array
    {
        $enums = [];
        $dbEnums = [];

        // 1. MySQL ENUM columns (as fallback)
        foreach ($this->allowedTables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            $columns = Schema::getColumns($tableName);
            foreach ($columns as $column) {
                if (str_starts_with($column['type'], 'enum')) {
                    // Parse enum('val1','val2') format
                    if (preg_match("/^enum\((.+)\)$/", $column['type'], $matches)) {
                        $values = str_getcsv($matches[1], ',', "'");
                        // Store with table prefix for disambiguation
                        $key = $tableName.'.'.$column['name'];
                        $dbEnums[$key] = $values;
                        // Also store by field name only (will be overwritten by validation rules)
                        if (! isset($dbEnums[$column['name']])) {
                            $dbEnums[$column['name']] = $values;
                        }
                    }
                }
            }
        }

        // 2. Validation rules from controllers (in:val1,val2 pattern) - these take precedence
        $controllersPath = app_path('Http/Controllers');
        $controllerFiles = $this->getPhpFilesRecursive($controllersPath);

        foreach ($controllerFiles as $file) {
            $content = File::get($file);

            // Match patterns like 'field_name' => 'in:val1,val2' or 'field_name' => '...in:val1,val2...'
            if (preg_match_all("/['\"](\w+)['\"]\s*=>\s*['\"][^'\"]*\bin:([^'\"|\s]+)/", $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $fieldName = $match[1];
                    $values = explode(',', $match[2]);

                    // Validation rules override DB enums for the same field
                    $enums[$fieldName] = $values;
                }
            }
        }

        // Merge DB enums for fields not found in validation rules
        foreach ($dbEnums as $field => $values) {
            // Skip table-prefixed keys, only use simple field names
            if (str_contains($field, '.')) {
                continue;
            }
            if (! isset($enums[$field])) {
                $enums[$field] = $values;
            }
        }

        return $enums;
    }

    /**
     * Discover query parameters by scanning controller methods.
     * Also detects common query parameter patterns and enriches with metadata.
     */
    protected function discoverQueryParams(): array
    {
        $params = [];

        // Common query parameter patterns with metadata
        $commonPatterns = $this->getCommonQueryParamPatterns();

        foreach (Route::getRoutes() as $route) {
            $uri = '/'.ltrim($route->uri(), '/');

            if (! str_starts_with($uri, '/api')) {
                continue;
            }

            $action = $route->getAction();
            if (! isset($action['controller'])) {
                continue;
            }

            // Parse Controller@method format
            $parts = explode('@', $action['controller']);
            if (count($parts) !== 2) {
                continue;
            }

            [$controllerClass, $methodName] = $parts;

            if (! class_exists($controllerClass)) {
                continue;
            }

            try {
                $reflection = new \ReflectionMethod($controllerClass, $methodName);
                $fileName = $reflection->getFileName();
                $startLine = $reflection->getStartLine();
                $endLine = $reflection->getEndLine();

                if (! $fileName || ! file_exists($fileName)) {
                    continue;
                }

                $lines = file($fileName);
                $methodCode = implode('', array_slice($lines, $startLine - 1, $endLine - $startLine + 1));

                // Find query param usage patterns
                $discoveredParams = [];

                // $request->has('param'), $request->get('param'), $request->input('param'), $request->query('param')
                if (preg_match_all('/\$request->(has|get|input|query)\([\'"](\w+)[\'"]\)/', $methodCode, $matches)) {
                    foreach ($matches[2] as $paramName) {
                        $discoveredParams[$paramName] = $this->buildParamSpec($paramName, $commonPatterns);
                    }
                }

                // $request->param (dynamic property access) - must be at least 3 chars and snake_case or camelCase
                // Use word boundary \b to ensure we match whole property names
                if (preg_match_all('/\$request->([a-z][a-z0-9_]{2,})\b(?!\s*\()/i', $methodCode, $matches)) {
                    foreach ($matches[1] as $paramName) {
                        // Skip common Request methods and properties
                        $skipList = [
                            'user', 'all', 'only', 'except', 'validated', 'file', 'files',
                            'headers', 'route', 'merge', 'ip', 'method', 'url', 'fullUrl',
                            'path', 'segment', 'segments', 'server', 'root', 'ajax', 'pjax',
                            'secure', 'getHost', 'getPort', 'cookie', 'session', 'old',
                            'flash', 'flush', 'boolean', 'date', 'enum', 'string', 'integer',
                            'float', 'collect', 'keys', 'whenFilled', 'whenHas', 'filled',
                            'missing', 'anyFilled', 'isNotFilled', 'rules', 'messages',
                        ];
                        if (in_array($paramName, $skipList)) {
                            continue;
                        }
                        // Skip if it looks like a method call that wasn't caught
                        if (strlen($paramName) < 3) {
                            continue;
                        }
                        $discoveredParams[$paramName] = $this->buildParamSpec($paramName, $commonPatterns);
                    }
                }

                if (! empty($discoveredParams)) {
                    foreach ($route->methods() as $method) {
                        if ($method === 'HEAD') {
                            continue;
                        }

                        // Only add query params for GET and DELETE - POST/PUT/PATCH use request body
                        if (! in_array($method, ['GET', 'DELETE'])) {
                            continue;
                        }

                        $key = $method.' '.$uri;
                        $params[$key] = array_values($discoveredParams);
                    }
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $params;
    }

    /**
     * Get common query parameter patterns with their metadata.
     * Maps parameter names to their type, category, description, and optional properties.
     */
    protected function getCommonQueryParamPatterns(): array
    {
        return [
            // Pagination
            'page' => [
                'type' => 'integer',
                'category' => 'pagination',
                'description' => 'Page number',
                'default' => 1,
            ],
            'per_page' => [
                'type' => 'integer',
                'category' => 'pagination',
                'description' => 'Items per page',
                'default' => 15,
            ],
            'limit' => [
                'type' => 'integer',
                'category' => 'pagination',
                'description' => 'Maximum number of items to return',
                'default' => 15,
            ],
            'offset' => [
                'type' => 'integer',
                'category' => 'pagination',
                'description' => 'Number of items to skip',
                'default' => 0,
            ],

            // Sorting
            'sort' => [
                'type' => 'string',
                'category' => 'sorting',
                'description' => 'Field to sort by',
            ],
            'sort_by' => [
                'type' => 'string',
                'category' => 'sorting',
                'description' => 'Field to sort by',
            ],
            'order_by' => [
                'type' => 'string',
                'category' => 'sorting',
                'description' => 'Field to sort by',
            ],
            'sort_direction' => [
                'type' => 'string',
                'category' => 'sorting',
                'description' => 'Sort direction',
                'enum' => ['asc', 'desc'],
                'default' => 'asc',
            ],
            'order' => [
                'type' => 'string',
                'category' => 'sorting',
                'description' => 'Sort direction',
                'enum' => ['asc', 'desc'],
                'default' => 'asc',
            ],
            'direction' => [
                'type' => 'string',
                'category' => 'sorting',
                'description' => 'Sort direction',
                'enum' => ['asc', 'desc'],
                'default' => 'asc',
            ],

            // Date Range
            'from' => [
                'type' => 'string',
                'format' => 'date',
                'category' => 'date_range',
                'description' => 'Start date filter (YYYY-MM-DD)',
            ],
            'to' => [
                'type' => 'string',
                'format' => 'date',
                'category' => 'date_range',
                'description' => 'End date filter (YYYY-MM-DD)',
            ],
            'start_date' => [
                'type' => 'string',
                'format' => 'date',
                'category' => 'date_range',
                'description' => 'Start date filter (YYYY-MM-DD)',
            ],
            'end_date' => [
                'type' => 'string',
                'format' => 'date',
                'category' => 'date_range',
                'description' => 'End date filter (YYYY-MM-DD)',
            ],

            // Search
            'search' => [
                'type' => 'string',
                'category' => 'search',
                'description' => 'Search query',
            ],
            'q' => [
                'type' => 'string',
                'category' => 'search',
                'description' => 'Search query',
            ],
            'query' => [
                'type' => 'string',
                'category' => 'search',
                'description' => 'Search query',
            ],

            // Relations
            'include' => [
                'type' => 'string',
                'category' => 'relations',
                'description' => 'Comma-separated list of relations to include',
            ],
            'with' => [
                'type' => 'string',
                'category' => 'relations',
                'description' => 'Comma-separated list of relations to include',
            ],
        ];
    }

    /**
     * Build a parameter specification with enriched metadata from common patterns.
     */
    protected function buildParamSpec(string $paramName, array $commonPatterns): array
    {
        $spec = [
            'name' => $paramName,
            'type' => $this->guessParamType($paramName),
            'required' => false,
        ];

        // Check if this is a known common pattern
        if (isset($commonPatterns[$paramName])) {
            $pattern = $commonPatterns[$paramName];
            $spec['type'] = $pattern['type'];
            $spec['category'] = $pattern['category'] ?? null;
            $spec['description'] = $pattern['description'] ?? null;

            if (isset($pattern['format'])) {
                $spec['format'] = $pattern['format'];
            }
            if (isset($pattern['enum'])) {
                $spec['enum'] = $pattern['enum'];
            }
            if (isset($pattern['default'])) {
                $spec['default'] = $pattern['default'];
            }
        }

        return $spec;
    }

    /**
     * Get common query parameter suggestions grouped by category.
     * Used for frontend UI to suggest common parameters.
     */
    protected function getCommonQueryParamSuggestions(): array
    {
        return [
            'pagination' => [
                ['name' => 'page', 'type' => 'integer', 'description' => 'Page number', 'default' => 1],
                ['name' => 'per_page', 'type' => 'integer', 'description' => 'Items per page', 'default' => 15],
            ],
            'sorting' => [
                ['name' => 'sort_by', 'type' => 'string', 'description' => 'Field to sort by'],
                ['name' => 'sort_direction', 'type' => 'string', 'description' => 'Sort direction', 'enum' => ['asc', 'desc'], 'default' => 'asc'],
            ],
            'date_range' => [
                ['name' => 'from', 'type' => 'string', 'format' => 'date', 'description' => 'Start date'],
                ['name' => 'to', 'type' => 'string', 'format' => 'date', 'description' => 'End date'],
            ],
            'search' => [
                ['name' => 'search', 'type' => 'string', 'description' => 'Search query'],
            ],
        ];
    }

    /**
     * Get all PHP files recursively from a directory.
     */
    protected function getPhpFilesRecursive(string $path): array
    {
        if (! is_dir($path)) {
            return [];
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Guess parameter type from name.
     */
    protected function guessParamType(string $name): string
    {
        $nameLower = strtolower($name);

        if (Str::endsWith($nameLower, '_id') || $nameLower === 'id') {
            return 'integer';
        }
        if (Str::startsWith($nameLower, 'is_') || Str::startsWith($nameLower, 'has_') || Str::startsWith($nameLower, 'get_')) {
            return 'boolean';
        }
        if (str_contains($nameLower, 'page') || str_contains($nameLower, 'limit') || str_contains($nameLower, 'count')) {
            return 'integer';
        }

        return 'string';
    }

    /**
     * Get enum values for a field name if discovered.
     */
    protected function getEnumForField(string $fieldName): ?array
    {
        return $this->discoveredEnums[$fieldName] ?? null;
    }

    /**
     * Convert OpenAPI 3.1.0 spec to 3.0.0 for Apidog compatibility.
     *
     * Main changes:
     * - "type": ["string", "null"] → "type": "string", "nullable": true
     * - "openapi": "3.1.0" → "openapi": "3.0.0"
     * - "const" → "enum" with single value
     */
    protected function convertToOpenApi30(array $spec): array
    {
        $spec['openapi'] = '3.0.0';
        $spec = $this->convertSchemaRecursive($spec);

        // Inject discovered query parameters
        $spec = $this->injectQueryParams($spec);

        // Inject discovered enums into schemas
        $spec = $this->injectEnumsIntoSpec($spec);

        if (! $this->option('no-examples')) {
            $spec = $this->addExamplesToRequestBodies($spec);
        }

        // Add common query param suggestions as custom extension for frontend
        $spec['x-common-query-params'] = $this->getCommonQueryParamSuggestions();

        return $spec;
    }

    /**
     * Inject discovered query parameters into the OpenAPI spec.
     * Enriches params with format, default, description, and adds related params.
     */
    protected function injectQueryParams(array $spec): array
    {
        $commonPatterns = $this->getCommonQueryParamPatterns();

        // Define related params that should be added together
        $relatedParams = [
            'page' => ['per_page'],
            'per_page' => ['page'],
            'sort' => ['sort_direction'],
            'sort_by' => ['sort_direction'],
            'order_by' => ['direction'],
            'from' => ['to'],
            'to' => ['from'],
            'start_date' => ['end_date'],
            'end_date' => ['start_date'],
        ];

        foreach ($spec['paths'] ?? [] as $path => $methods) {
            foreach ($methods as $method => $operation) {
                if (! is_array($operation)) {
                    continue;
                }

                $fullUri = '/api'.$path;
                $key = strtoupper($method).' '.$fullUri;

                if (! isset($this->discoveredQueryParams[$key])) {
                    continue;
                }

                $existingParams = $operation['parameters'] ?? [];
                $existingNames = array_column(
                    array_filter($existingParams, fn ($p) => ($p['in'] ?? '') === 'query'),
                    'name'
                );

                // Track which params we're adding to avoid duplicates
                $addedNames = [];

                foreach ($this->discoveredQueryParams[$key] as $param) {
                    // Skip if already exists in spec
                    if (in_array($param['name'], $existingNames)) {
                        continue;
                    }

                    // Skip if we already added this param
                    if (in_array($param['name'], $addedNames)) {
                        continue;
                    }

                    $paramSpec = $this->buildOpenApiParamSpec($param, $commonPatterns);
                    $spec['paths'][$path][$method]['parameters'][] = $paramSpec;
                    $addedNames[] = $param['name'];

                    // Add related params if missing
                    if (isset($relatedParams[$param['name']])) {
                        foreach ($relatedParams[$param['name']] as $relatedName) {
                            // Skip if already exists or already added
                            if (in_array($relatedName, $existingNames) || in_array($relatedName, $addedNames)) {
                                continue;
                            }

                            // Build spec for related param from common patterns
                            if (isset($commonPatterns[$relatedName])) {
                                $relatedParam = array_merge(
                                    ['name' => $relatedName, 'required' => false],
                                    $commonPatterns[$relatedName]
                                );
                                $relatedSpec = $this->buildOpenApiParamSpec($relatedParam, $commonPatterns);
                                $spec['paths'][$path][$method]['parameters'][] = $relatedSpec;
                                $addedNames[] = $relatedName;
                            }
                        }
                    }
                }
            }
        }

        return $spec;
    }

    /**
     * Build an OpenAPI parameter specification with full metadata.
     */
    protected function buildOpenApiParamSpec(array $param, array $commonPatterns): array
    {
        $paramSpec = [
            'name' => $param['name'],
            'in' => 'query',
            'required' => $param['required'] ?? false,
            'schema' => ['type' => $param['type'] ?? 'string'],
        ];

        // Add description
        if (! empty($param['description'])) {
            $paramSpec['description'] = $param['description'];
        }

        // Add format (e.g., date, date-time)
        if (! empty($param['format'])) {
            $paramSpec['schema']['format'] = $param['format'];
        }

        // Add default value
        if (isset($param['default'])) {
            $paramSpec['schema']['default'] = $param['default'];
        }

        // Add enum values - from param spec or discovered enums
        if (! empty($param['enum'])) {
            $paramSpec['schema']['enum'] = $param['enum'];
        } else {
            $enumValues = $this->getEnumForField($param['name']);
            if ($enumValues) {
                $paramSpec['schema']['enum'] = $enumValues;
                if (empty($paramSpec['description'])) {
                    $paramSpec['description'] = 'Options: '.implode(', ', $enumValues);
                }
            }
        }

        return $paramSpec;
    }

    /**
     * Inject discovered enum values into schema properties.
     */
    protected function injectEnumsIntoSpec(array $spec): array
    {
        // Inject into component schemas
        foreach ($spec['components']['schemas'] ?? [] as $name => $schema) {
            $spec['components']['schemas'][$name] = $this->addEnumsToSchema($schema);
        }

        // Inject into inline request body schemas
        foreach ($spec['paths'] ?? [] as $path => $methods) {
            foreach ($methods as $method => $operation) {
                if (! is_array($operation)) {
                    continue;
                }

                if (isset($operation['requestBody']['content']['application/json']['schema'])) {
                    $spec['paths'][$path][$method]['requestBody']['content']['application/json']['schema'] =
                        $this->addEnumsToSchema($operation['requestBody']['content']['application/json']['schema']);
                }

                // Also inject into response schemas
                foreach ($operation['responses'] ?? [] as $code => $response) {
                    if (isset($response['content']['application/json']['schema'])) {
                        $spec['paths'][$path][$method]['responses'][$code]['content']['application/json']['schema'] =
                            $this->addEnumsToSchema($response['content']['application/json']['schema']);
                    }
                }
            }
        }

        return $spec;
    }

    /**
     * Add discovered enum values to schema properties.
     */
    protected function addEnumsToSchema(array $schema): array
    {
        if (! isset($schema['properties']) || ! is_array($schema['properties'])) {
            return $schema;
        }

        foreach ($schema['properties'] as $name => $prop) {
            // Handle bare string type (e.g., "type": "string" instead of "type": {"type": "string"})
            // Only inject enum if not already present in original spec
            if (is_string($prop)) {
                // Bare string has no enum, so we can safely add discovered one
                $enumValues = $this->getEnumForField($name);
                if ($enumValues) {
                    $schema['properties'][$name] = [
                        'type' => $prop,
                        'enum' => $enumValues,
                        'description' => 'Options: '.implode(', ', $enumValues),
                    ];
                }

                continue;
            }

            if (! is_array($prop)) {
                continue;
            }

            // Skip if already has enum
            if (isset($prop['enum'])) {
                // But add description with options if missing
                if (! isset($prop['description']) && ! empty($prop['enum'])) {
                    $schema['properties'][$name]['description'] = 'Options: '.implode(', ', $prop['enum']);
                }

                continue;
            }

            // Skip $ref
            if (isset($prop['$ref'])) {
                continue;
            }

            // Recurse into nested objects
            if (($prop['type'] ?? '') === 'object') {
                $schema['properties'][$name] = $this->addEnumsToSchema($prop);

                continue;
            }

            // Add discovered enum
            $enumValues = $this->getEnumForField($name);
            if ($enumValues) {
                $schema['properties'][$name]['enum'] = $enumValues;
                $schema['properties'][$name]['description'] = 'Options: '.implode(', ', $enumValues);
            }
        }

        return $schema;
    }

    /**
     * Add example values to request body schemas so Apidog imports don't overwrite saved examples.
     */
    protected function addExamplesToRequestBodies(array $spec): array
    {
        // Add property-level examples to component schemas (used by $ref)
        foreach ($spec['components']['schemas'] ?? [] as $name => $schema) {
            $spec['components']['schemas'][$name] = $this->addPropertyExamples($schema);
        }

        // Add examples to inline request body schemas + media-type level example
        foreach ($spec['paths'] ?? [] as $path => $methods) {
            foreach ($methods as $method => $operation) {
                if (! is_array($operation)) {
                    continue;
                }

                if (isset($operation['requestBody']['content']['application/json']['schema'])) {
                    $schema = $operation['requestBody']['content']['application/json']['schema'];

                    // Add property-level examples to inline schemas
                    $spec['paths'][$path][$method]['requestBody']['content']['application/json']['schema'] =
                        $this->addPropertyExamples($schema);

                    // Add a complete example object at the media-type level
                    $example = $this->schemaToExample($schema);
                    if (! empty($example) && is_array($example)) {
                        $spec['paths'][$path][$method]['requestBody']['content']['application/json']['example'] = $example;
                    }
                }
            }
        }

        return $spec;
    }

    /**
     * Add example values to each property in a schema.
     */
    protected function addPropertyExamples(array $schema): array
    {
        if (! isset($schema['properties']) || ! is_array($schema['properties'])) {
            return $schema;
        }

        foreach ($schema['properties'] as $name => $prop) {
            if (! is_array($prop)) {
                continue;
            }

            // Skip $ref properties — they'll get examples from their component schema
            if (isset($prop['$ref'])) {
                continue;
            }

            // Recurse into nested objects
            if (($prop['type'] ?? '') === 'object') {
                $schema['properties'][$name] = $this->addPropertyExamples($prop);
            }

            // Add example if not already present
            if (! isset($prop['example'])) {
                $example = $this->generatePropertyExample($name, $prop);
                if ($example !== null) {
                    $schema['properties'][$name]['example'] = $example;
                }
            }
        }

        return $schema;
    }

    /**
     * Generate a contextual example value based on property name and schema.
     * Uses pattern matching on common field naming conventions to generate realistic examples.
     */
    protected function generatePropertyExample(string $name, array $prop): mixed
    {
        // Enum: use first value
        if (isset($prop['enum'])) {
            return $prop['enum'][0];
        }

        $type = $prop['type'] ?? 'string';
        $format = $prop['format'] ?? null;
        $nameLower = strtolower($name);

        // ============ FORMAT-BASED EXAMPLES (highest priority) ============
        if ($format === 'date-time') {
            return now()->toIso8601String();
        }
        if ($format === 'date') {
            return now()->toDateString();
        }
        if ($format === 'email') {
            return 'user@example.com';
        }
        if ($format === 'uri' || $format === 'url') {
            return 'https://example.com';
        }
        if ($format === 'uuid') {
            return '550e8400-e29b-41d4-a716-446655440000';
        }

        // ============ ID PATTERNS ============
        if ($nameLower === 'id' || Str::endsWith($nameLower, '_id')) {
            return 1;
        }

        // ============ EMAIL PATTERNS ============
        if (str_contains($nameLower, 'email')) {
            return 'user@example.com';
        }

        // ============ NAME/TITLE PATTERNS ============
        if ($nameLower === 'name' || $nameLower === 'title') {
            return 'Example Name';
        }
        if (Str::endsWith($nameLower, '_name')) {
            return 'Example Name';
        }
        if (Str::endsWith($nameLower, '_title')) {
            return 'Example Title';
        }
        if ($nameLower === 'first_name' || $nameLower === 'firstname') {
            return 'John';
        }
        if ($nameLower === 'last_name' || $nameLower === 'lastname') {
            return 'Doe';
        }
        if ($nameLower === 'full_name' || $nameLower === 'fullname') {
            return 'John Doe';
        }
        if ($nameLower === 'username') {
            return 'johndoe';
        }

        // ============ PHONE PATTERNS ============
        if (str_contains($nameLower, 'phone') || str_contains($nameLower, 'mobile') || str_contains($nameLower, 'tel')) {
            return '+1234567890';
        }

        // ============ PASSWORD PATTERNS ============
        if (str_contains($nameLower, 'password')) {
            return 'Password123!';
        }
        if ($nameLower === 'password_confirmation') {
            return 'Password123!';
        }

        // ============ URL/LINK PATTERNS ============
        if (str_contains($nameLower, 'url') || str_contains($nameLower, 'link') || str_contains($nameLower, 'website')) {
            return 'https://example.com';
        }
        if (str_contains($nameLower, 'image') || str_contains($nameLower, 'avatar') || str_contains($nameLower, 'photo')) {
            return 'https://example.com/image.jpg';
        }

        // ============ DATE/TIME PATTERNS ============
        if (str_contains($nameLower, 'date') || Str::endsWith($nameLower, '_at')) {
            return now()->toDateString();
        }
        if (str_contains($nameLower, 'time')) {
            return now()->toTimeString();
        }

        // ============ MONEY/AMOUNT PATTERNS ============
        if (str_contains($nameLower, 'amount') || str_contains($nameLower, 'price') ||
            str_contains($nameLower, 'balance') || str_contains($nameLower, 'payment') ||
            str_contains($nameLower, 'total') || str_contains($nameLower, 'cost') ||
            str_contains($nameLower, 'fee') || str_contains($nameLower, 'value')) {
            return 1000.00;
        }

        // ============ PERCENTAGE/RATE PATTERNS ============
        if (str_contains($nameLower, 'percentage') || str_contains($nameLower, 'percent')) {
            return 5.5;
        }
        if (str_contains($nameLower, 'rate')) {
            return 5.0;
        }

        // ============ QUANTITY/COUNT PATTERNS ============
        if (str_contains($nameLower, 'quantity') || str_contains($nameLower, 'qty') ||
            str_contains($nameLower, 'count') || str_contains($nameLower, 'num')) {
            return 10;
        }

        // ============ DAY/MONTH/YEAR PATTERNS ============
        if (Str::endsWith($nameLower, '_day') || $nameLower === 'day') {
            return 15;
        }
        if (Str::endsWith($nameLower, '_month') || $nameLower === 'month') {
            return 6;
        }
        if (Str::endsWith($nameLower, '_year') || $nameLower === 'year') {
            return 2024;
        }
        if (Str::endsWith($nameLower, '_months') || Str::endsWith($nameLower, '_years')) {
            return 12;
        }

        // ============ TEXT/DESCRIPTION PATTERNS ============
        if (str_contains($nameLower, 'description') || str_contains($nameLower, 'note') ||
            str_contains($nameLower, 'comment') || str_contains($nameLower, 'content')) {
            return 'Lorem ipsum description text';
        }
        if (str_contains($nameLower, 'summary') || str_contains($nameLower, 'excerpt')) {
            return 'Brief summary of the content';
        }

        // ============ CURRENCY PATTERNS ============
        if ($nameLower === 'currency' || $nameLower === 'currency_code') {
            return 'USD';
        }

        // ============ COUNTRY/LOCALE PATTERNS ============
        if ($nameLower === 'country' || $nameLower === 'country_code') {
            return 'US';
        }
        if ($nameLower === 'locale' || $nameLower === 'language') {
            return 'en';
        }

        // ============ ADDRESS PATTERNS ============
        if (str_contains($nameLower, 'address') && ! str_contains($nameLower, 'email')) {
            return '123 Main Street';
        }
        if ($nameLower === 'city') {
            return 'New York';
        }
        if ($nameLower === 'state' || $nameLower === 'province') {
            return 'NY';
        }
        if (str_contains($nameLower, 'zip') || str_contains($nameLower, 'postal')) {
            return '10001';
        }

        // ============ COLOR PATTERNS ============
        if (str_contains($nameLower, 'color') || str_contains($nameLower, 'colour')) {
            return '#3B82F6';
        }

        // ============ BOOLEAN PATTERNS ============
        if (Str::startsWith($nameLower, 'is_') || Str::startsWith($nameLower, 'has_') ||
            Str::startsWith($nameLower, 'can_') || Str::startsWith($nameLower, 'should_') ||
            str_contains($nameLower, 'enabled') || str_contains($nameLower, 'active') ||
            str_contains($nameLower, 'verified') || str_contains($nameLower, 'confirmed')) {
            return true;
        }

        // ============ CODE/TOKEN PATTERNS ============
        if (str_contains($nameLower, 'token')) {
            return 'abc123def456';
        }
        if (str_contains($nameLower, 'code') && ! str_contains($nameLower, 'postal') && ! str_contains($nameLower, 'country')) {
            return 'ABC123';
        }

        // ============ TYPE-BASED FALLBACK ============
        return match ($type) {
            'integer' => 1,
            'number' => 0.00,
            'boolean' => false,
            'string' => Str::headline($name),
            'array' => [],
            'object' => new \stdClass,
            default => null,
        };
    }

    protected function convertSchemaRecursive(array $data): array
    {
        // Convert type arrays to single type + nullable
        // Only if it's a type array like ["string", "null"], not a schema object
        if (isset($data['type']) && is_array($data['type']) && $this->isTypeArray($data['type'])) {
            $types = array_values(array_filter($data['type'], fn ($t) => $t !== 'null'));
            $hasNull = in_array('null', $data['type']);

            $data['type'] = $types[0] ?? 'string';

            if ($hasNull) {
                $data['nullable'] = true;
            }
        }

        // Convert "const" to "enum" (3.1.0 feature not in 3.0.0)
        if (isset($data['const'])) {
            $data['enum'] = [$data['const']];
            unset($data['const']);
        }

        // Recurse into all nested arrays
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->convertSchemaRecursive($value);
            }
        }

        return $data;
    }

    /**
     * Check if an array is a JSON Schema type array (e.g., ["string", "null"]).
     * Type arrays contain only scalar type names, not nested objects.
     */
    protected function isTypeArray(array $arr): bool
    {
        $validTypes = ['string', 'integer', 'number', 'boolean', 'array', 'object', 'null'];

        foreach ($arr as $item) {
            if (! is_string($item) || ! in_array($item, $validTypes)) {
                return false;
            }
        }

        return true;
    }
}
