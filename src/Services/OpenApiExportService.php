<?php

namespace Apiura\Services;

use Apiura\Models\SavedApiRequest;
use Illuminate\Support\Str;

class OpenApiExportService
{
    public function export(array $spec, string $mode): array
    {
        return match ($mode) {
            'with-cases' => $this->exportWithCases($spec),
            'with-examples' => $this->exportWithExamples($spec),
            'clean' => $this->exportClean($spec),
            default => $spec,
        };
    }

    /**
     * Inject ALL saved API request cases as named examples into the spec.
     * Uses OpenAPI 3.0 `examples` (plural) with named entries so tools like Apidog
     * display them as selectable test cases.
     */
    protected function exportWithCases(array $spec): array
    {
        $cases = SavedApiRequest::whereNotNull('body')
            ->orderByDesc('updated_at')
            ->get(['id', 'name', 'method', 'path', 'body']);

        // Group all cases by lowercase method:path
        $casesByEndpoint = [];
        foreach ($cases as $case) {
            $body = $case->body;
            if (! is_array($body) || empty($body)) {
                continue;
            }

            $key = strtolower($case->method) . ':' . $case->path;
            $casesByEndpoint[$key][] = [
                'name' => $case->name ?: "Case #{$case->id}",
                'body' => $body,
            ];
        }

        foreach ($spec['paths'] ?? [] as $path => $methods) {
            foreach ($methods as $method => $operation) {
                if (! is_array($operation)) {
                    continue;
                }

                $key = strtolower($method) . ':' . $path;
                if (isset($casesByEndpoint[$key])) {
                    $savedCases = $casesByEndpoint[$key];

                    // Build named examples map for the `examples` field
                    $namedExamples = [];
                    foreach ($savedCases as $i => $saved) {
                        $slug = Str::slug($saved['name'], '_') ?: "case_{$i}";
                        // Ensure unique keys
                        $exampleKey = $slug;
                        $counter = 1;
                        while (isset($namedExamples[$exampleKey])) {
                            $exampleKey = $slug . '_' . $counter++;
                        }

                        $namedExamples[$exampleKey] = [
                            'summary' => $saved['name'],
                            'value' => $saved['body'],
                        ];
                    }

                    // Set the `examples` (plural) field — Apidog picks these up as test cases
                    $spec['paths'][$path][$method]['requestBody']['content']['application/json']['examples'] = $namedExamples;

                    // Also set `example` (singular) to the most recent case for tools that only read that
                    $mostRecent = $savedCases[0]['body'];
                    $spec['paths'][$path][$method]['requestBody']['content']['application/json']['example'] = $mostRecent;

                    // Set per-property examples from the most recent case
                    if (isset($spec['paths'][$path][$method]['requestBody']['content']['application/json']['schema']['properties'])) {
                        $properties = &$spec['paths'][$path][$method]['requestBody']['content']['application/json']['schema']['properties'];
                        foreach ($mostRecent as $field => $value) {
                            if (isset($properties[$field]) && is_array($properties[$field])) {
                                $properties[$field]['example'] = $value;
                            }
                        }
                        unset($properties);
                    }
                } else {
                    // No saved cases — generate examples from schema
                    $schema = $spec['paths'][$path][$method]['requestBody']['content']['application/json']['schema'] ?? null;
                    if ($schema) {
                        $generated = $this->generateExampleFromSchema($schema, $spec);
                        if (! empty($generated)) {
                            $spec['paths'][$path][$method]['requestBody']['content']['application/json']['example'] = $generated;
                            $spec['paths'][$path][$method]['requestBody']['content']['application/json']['examples'] = [
                                'generated' => [
                                    'summary' => 'Auto-generated example',
                                    'value' => $generated,
                                ],
                            ];

                            // Set per-property examples
                            if (isset($spec['paths'][$path][$method]['requestBody']['content']['application/json']['schema']['properties'])) {
                                $properties = &$spec['paths'][$path][$method]['requestBody']['content']['application/json']['schema']['properties'];
                                foreach ($generated as $field => $value) {
                                    if (isset($properties[$field]) && is_array($properties[$field])) {
                                        $properties[$field]['example'] = $value;
                                    }
                                }
                                unset($properties);
                            }
                        }
                    }
                }
            }
        }

        return $spec;
    }

    /**
     * Return spec as-is (already contains generated examples).
     */
    protected function exportWithExamples(array $spec): array
    {
        return $spec;
    }

    /**
     * Recursively remove all 'example' and 'examples' keys from the spec.
     */
    protected function exportClean(array $spec): array
    {
        return $this->stripExamples($spec);
    }

    /**
     * Generate example values from a JSON schema definition.
     */
    protected function generateExampleFromSchema(array $schema, array $spec): array
    {
        // Resolve $ref
        if (isset($schema['$ref'])) {
            $refPath = str_replace('#/components/schemas/', '', $schema['$ref']);
            $schema = $spec['components']['schemas'][$refPath] ?? [];
        }

        $result = [];
        $properties = $schema['properties'] ?? [];

        foreach ($properties as $name => $prop) {
            // Resolve $ref in property
            if (isset($prop['$ref'])) {
                $refPath = str_replace('#/components/schemas/', '', $prop['$ref']);
                $resolved = $spec['components']['schemas'][$refPath] ?? [];
                if (! empty($resolved)) {
                    $result[$name] = $this->generateExampleFromSchema($resolved, $spec);
                    continue;
                }
            }

            // Use existing example if present
            if (isset($prop['example'])) {
                $result[$name] = $prop['example'];
                continue;
            }

            // Use first enum value
            if (isset($prop['enum'][0])) {
                $result[$name] = $prop['enum'][0];
                continue;
            }

            $type = $prop['type'] ?? 'string';
            if (is_array($type)) {
                $type = collect($type)->first(fn ($t) => $t !== 'null') ?? 'string';
            }

            switch ($type) {
                case 'object':
                    if (isset($prop['properties'])) {
                        $result[$name] = $this->generateExampleFromSchema($prop, $spec);
                    } else {
                        $result[$name] = new \stdClass;
                    }
                    break;

                case 'array':
                    $items = $prop['items'] ?? [];
                    if (isset($items['$ref'])) {
                        $refPath = str_replace('#/components/schemas/', '', $items['$ref']);
                        $itemSchema = $spec['components']['schemas'][$refPath] ?? [];
                        $result[$name] = [! empty($itemSchema) ? $this->generateExampleFromSchema($itemSchema, $spec) : new \stdClass];
                    } elseif (isset($items['properties'])) {
                        $result[$name] = [$this->generateExampleFromSchema($items, $spec)];
                    } else {
                        $result[$name] = [$this->generateScalarExample($items, $name)];
                    }
                    break;

                case 'boolean':
                    $result[$name] = true;
                    break;

                case 'integer':
                    $result[$name] = $this->guessIntegerValue($name);
                    break;

                case 'number':
                    $result[$name] = $this->guessNumberValue($name);
                    break;

                default: // string
                    $result[$name] = $this->guessStringValue($name, $prop['format'] ?? null);
                    break;
            }
        }

        return $result;
    }

    protected function guessStringValue(string $name, ?string $format = null): string
    {
        if ($format === 'email' || str_contains($name, 'email')) {
            return 'user@example.com';
        }
        if ($format === 'uuid') {
            return '550e8400-e29b-41d4-a716-446655440000';
        }
        if ($format === 'uri' || $format === 'url' || str_contains($name, 'url') || str_contains($name, 'link')) {
            return 'https://example.com';
        }
        if ($format === 'date-time') {
            return now()->toIso8601String();
        }
        if ($format === 'date') {
            return now()->toDateString();
        }

        $lower = strtolower($name);
        if (str_contains($lower, 'phone') || str_contains($lower, 'mobile')) {
            return '+1234567890';
        }
        if (str_contains($lower, 'name')) {
            return 'example_name';
        }
        if (str_contains($lower, 'password') || str_contains($lower, 'secret')) {
            return 'password123';
        }
        if (str_contains($lower, 'description') || str_contains($lower, 'note') || str_contains($lower, 'comment')) {
            return 'Sample text';
        }
        if (str_contains($lower, 'color') || str_contains($lower, 'colour')) {
            return '#FF5733';
        }
        if (str_contains($lower, 'currency')) {
            return 'USD';
        }
        if (str_contains($lower, 'status')) {
            return 'active';
        }
        if (str_contains($lower, 'type') || str_contains($lower, 'kind') || str_contains($lower, 'category')) {
            return 'default';
        }
        if (str_contains($lower, 'title')) {
            return 'Sample Title';
        }
        if (str_contains($lower, 'slug')) {
            return 'example-slug';
        }
        if (str_contains($lower, 'code')) {
            return 'ABC123';
        }

        return 'string';
    }

    protected function guessIntegerValue(string $name): int
    {
        $lower = strtolower($name);
        if (str_contains($lower, 'amount') || str_contains($lower, 'price') || str_contains($lower, 'total')) {
            return 100;
        }
        if (str_contains($lower, 'quantity') || str_contains($lower, 'count') || str_contains($lower, 'per_page')) {
            return 10;
        }
        if (str_contains($lower, 'page')) {
            return 1;
        }
        if (str_contains($lower, 'id')) {
            return 1;
        }

        return 1;
    }

    protected function guessNumberValue(string $name): float
    {
        $lower = strtolower($name);
        if (str_contains($lower, 'amount') || str_contains($lower, 'price') || str_contains($lower, 'total')) {
            return 100.0;
        }
        if (str_contains($lower, 'rate') || str_contains($lower, 'percent')) {
            return 50.0;
        }

        return 1.0;
    }

    /**
     * Generate scalar example for array items.
     */
    protected function generateScalarExample(array $schema, string $parentName = ''): mixed
    {
        if (isset($schema['example'])) {
            return $schema['example'];
        }
        if (isset($schema['enum'][0])) {
            return $schema['enum'][0];
        }

        $type = $schema['type'] ?? 'string';
        if (is_array($type)) {
            $type = collect($type)->first(fn ($t) => $t !== 'null') ?? 'string';
        }

        return match ($type) {
            'integer' => $this->guessIntegerValue($parentName),
            'number' => $this->guessNumberValue($parentName),
            'boolean' => true,
            default => $this->guessStringValue($parentName, $schema['format'] ?? null),
        };
    }

    protected function stripExamples(array $data): array
    {
        unset($data['example'], $data['examples']);

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->stripExamples($value);
            }
        }

        return $data;
    }
}
