<?php

namespace Apiura\Services;

use Apiura\Models\ApiuraModule;
use Apiura\Models\SavedApiFlow;
use Apiura\Models\SavedApiRequest;

class ModuleDuplicateDetectionService
{
    /**
     * Check incoming requests against existing requests in a module.
     *
     * @param  int|null  $moduleId  Module to check against (null = unorganized)
     * @param  array  $incomingRequests  Array of incoming request data
     * @return array Annotated incoming items with duplicate info
     */
    public function checkRequestDuplicates(?int $moduleId, array $incomingRequests): array
    {
        $existing = SavedApiRequest::where('module_id', $moduleId)->get();

        return array_map(function ($incoming) use ($existing) {
            $bestMatch = null;
            $bestScore = 0;

            foreach ($existing as $ex) {
                $score = $this->scoreRequestSimilarity($incoming, $ex);
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestMatch = $ex;
                }
            }

            $incoming['duplicate'] = $this->buildDuplicateResult($bestMatch, $bestScore, 'request');

            return $incoming;
        }, $incomingRequests);
    }

    /**
     * Check incoming flows against existing flows in a module.
     *
     * @param  int|null  $moduleId  Module to check against (null = unorganized)
     * @param  array  $incomingFlows  Array of incoming flow data
     * @return array Annotated incoming items with duplicate info
     */
    public function checkFlowDuplicates(?int $moduleId, array $incomingFlows): array
    {
        $existing = SavedApiFlow::where('module_id', $moduleId)->get();

        return array_map(function ($incoming) use ($existing) {
            $bestMatch = null;
            $bestScore = 0;

            foreach ($existing as $ex) {
                $score = $this->scoreFlowSimilarity($incoming, $ex);
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestMatch = $ex;
                }
            }

            $incoming['duplicate'] = $this->buildDuplicateResult($bestMatch, $bestScore, 'flow');

            return $incoming;
        }, $incomingFlows);
    }

    /**
     * Score similarity between incoming request and existing request.
     * Method+path: 50pts, body similarity: 30pts, name similarity: 20pts
     */
    private function scoreRequestSimilarity(array $incoming, SavedApiRequest $existing): int
    {
        $score = 0;

        // Method + path match (50 points)
        $incomingMethod = strtoupper($incoming['method'] ?? '');
        $incomingPath = $incoming['path'] ?? '';

        if ($incomingMethod === strtoupper($existing->method) && $incomingPath === $existing->path) {
            $score += 50;
        } elseif ($incomingPath === $existing->path) {
            $score += 25;
        }

        // Body similarity (30 points)
        $incomingBody = $incoming['body'] ?? [];
        $existingBody = $existing->body ?? [];
        if (is_string($incomingBody)) {
            $incomingBody = json_decode($incomingBody, true) ?? [];
        }
        $score += $this->compareStructures($incomingBody, $existingBody, 30);

        // Name similarity (20 points)
        $incomingName = $incoming['name'] ?? '';
        $existingName = $existing->name ?? '';
        if ($incomingName && $existingName) {
            similar_text(strtolower($incomingName), strtolower($existingName), $pct);
            $score += (int) round($pct / 100 * 20);
        }

        return $score;
    }

    /**
     * Score similarity between incoming flow and existing flow.
     * Name: 30pts, steps structural similarity: 70pts
     */
    private function scoreFlowSimilarity(array $incoming, SavedApiFlow $existing): int
    {
        $score = 0;

        // Name similarity (30 points)
        $incomingName = $incoming['name'] ?? '';
        $existingName = $existing->name ?? '';
        if ($incomingName && $existingName) {
            similar_text(strtolower($incomingName), strtolower($existingName), $pct);
            $score += (int) round($pct / 100 * 30);
        }

        // Steps structural similarity (70 points)
        $incomingSteps = $incoming['steps'] ?? [];
        $existingSteps = $existing->steps ?? [];

        if (empty($incomingSteps) && empty($existingSteps)) {
            $score += 70;
        } elseif (empty($incomingSteps) || empty($existingSteps)) {
            $score += 0;
        } else {
            $stepCount = max(count($incomingSteps), count($existingSteps));
            $matchScore = 0;

            foreach ($incomingSteps as $i => $inStep) {
                if (! isset($existingSteps[$i])) {
                    continue;
                }
                $exStep = $existingSteps[$i];

                // Compare endpoint method+path
                $inMethod = strtoupper($inStep['endpoint']['method'] ?? '');
                $exMethod = strtoupper($exStep['endpoint']['method'] ?? '');
                $inPath = $inStep['endpoint']['path'] ?? '';
                $exPath = $exStep['endpoint']['path'] ?? '';

                if ($inMethod === $exMethod && $inPath === $exPath) {
                    $matchScore += 1.0;
                } elseif ($inPath === $exPath) {
                    $matchScore += 0.5;
                }
            }

            $score += (int) round(($matchScore / $stepCount) * 70);
        }

        return $score;
    }

    /**
     * Compare two data structures for similarity.
     */
    private function compareStructures($a, $b, int $maxPoints): int
    {
        if (! is_array($a) || ! is_array($b)) {
            return $a == $b ? $maxPoints : 0;
        }

        if (empty($a) && empty($b)) {
            return $maxPoints;
        }

        if (empty($a) || empty($b)) {
            return 0;
        }

        $allKeys = array_unique(array_merge(array_keys($a), array_keys($b)));
        $matchingKeys = 0;

        foreach ($allKeys as $key) {
            if (array_key_exists($key, $a) && array_key_exists($key, $b)) {
                $matchingKeys++;
            }
        }

        return (int) round(($matchingKeys / count($allKeys)) * $maxPoints);
    }

    /**
     * Build the duplicate result structure.
     */
    private function buildDuplicateResult(?object $match, int $score, string $type): array
    {
        if (! $match || $score < 50) {
            return [
                'is_duplicate' => false,
                'confidence' => $score,
                'matched_item' => null,
                'reason' => 'new item',
            ];
        }

        $matchedItem = ['id' => $match->id, 'name' => $match->name];
        if ($type === 'request') {
            $matchedItem['method'] = $match->method;
            $matchedItem['path'] = $match->path;
        } else {
            $matchedItem['steps_count'] = count($match->steps ?? []);
        }

        $reason = $score >= 80 ? 'high similarity match' : 'possible match';
        if ($type === 'request' && isset($match->method, $match->path)) {
            $reason = $score >= 80 ? 'same method and path, similar body' : 'similar endpoint';
        } elseif ($type === 'flow') {
            $reason = $score >= 80 ? 'similar name and step structure' : 'partially similar flow';
        }

        return [
            'is_duplicate' => $score >= 80,
            'confidence' => $score,
            'matched_item' => $matchedItem,
            'reason' => $reason,
        ];
    }
}
