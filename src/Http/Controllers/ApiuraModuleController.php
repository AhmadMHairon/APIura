<?php

namespace Apiura\Http\Controllers;

use Apiura\Http\Requests\StoreApiuraModuleRequest;
use Apiura\Http\Requests\UpdateApiuraModuleRequest;
use Apiura\Http\Resources\ApiuraModuleResource;
use Apiura\Models\ApiuraModule;
use Apiura\Models\SavedApiFlow;
use Apiura\Models\SavedApiRequest;
use Apiura\Services\ModuleDuplicateDetectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApiuraModuleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $withItems = $request->boolean('with_items');

        $childLoader = function ($q) use ($withItems) {
            $q->withCount(['savedRequests', 'savedFlows'])->orderBy('sort_order');
            if ($withItems) {
                $q->with(['savedFlows', 'savedRequests']);
            }
        };

        $query = ApiuraModule::with(['children' => function ($q) use ($childLoader, $withItems) {
            $q->withCount(['savedRequests', 'savedFlows'])->orderBy('sort_order');
            if ($withItems) {
                $q->with(['savedFlows', 'savedRequests']);
            }
            $q->with(['children' => $childLoader]);
        }])->withCount(['savedRequests', 'savedFlows']);

        if ($withItems) {
            $query->with(['savedFlows', 'savedRequests']);
        }

        if ($request->has('flat') && $request->input('flat') === 'true') {
            $modules = ApiuraModule::withCount(['savedRequests', 'savedFlows'])
                ->orderBy('sort_order')
                ->get();

            return ApiuraModuleResource::collection($modules)->response();
        }

        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->input('parent_id'));
        } else {
            $query->whereNull('parent_id');
        }

        $modules = $query->orderBy('sort_order')->get();

        return ApiuraModuleResource::collection($modules)->response();
    }

    public function store(StoreApiuraModuleRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (! empty($data['parent_id'])) {
            $parent = ApiuraModule::find($data['parent_id']);
            if ($parent && $parent->depth >= 2) {
                return response()->json([
                    'message' => 'Maximum nesting depth of 3 levels exceeded.',
                ], 422);
            }
        }

        $maxSort = ApiuraModule::where('parent_id', $data['parent_id'] ?? null)->max('sort_order');
        $data['sort_order'] = ($maxSort ?? -1) + 1;

        $module = ApiuraModule::create($data);

        return (new ApiuraModuleResource($module->loadCount(['savedRequests', 'savedFlows'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(ApiuraModule $module): JsonResponse
    {
        $module->load([
            'children' => function ($q) {
                $q->withCount(['savedRequests', 'savedFlows'])->orderBy('sort_order');
            },
            'savedRequests',
            'savedFlows',
        ])->loadCount(['savedRequests', 'savedFlows']);

        return (new ApiuraModuleResource($module))->response();
    }

    public function update(UpdateApiuraModuleRequest $request, ApiuraModule $module): JsonResponse
    {
        $module->update($request->validated());

        return (new ApiuraModuleResource($module->loadCount(['savedRequests', 'savedFlows'])))->response();
    }

    public function destroy(Request $request, ApiuraModule $module): JsonResponse
    {
        if ($request->boolean('delete_items')) {
            $moduleIds = collect([$module->id]);
            $children = $module->children;
            while ($children->isNotEmpty()) {
                $moduleIds = $moduleIds->merge($children->pluck('id'));
                $children = ApiuraModule::whereIn('parent_id', $children->pluck('id'))->get();
            }

            SavedApiFlow::whereIn('module_id', $moduleIds)->delete();
            SavedApiRequest::whereIn('module_id', $moduleIds)->delete();
        }

        $module->delete();

        return response()->json(['message' => 'Module deleted successfully']);
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:apiura_modules,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->input('items') as $item) {
            ApiuraModule::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Reorder successful']);
    }

    public function moveItems(Request $request): JsonResponse
    {
        $request->validate([
            'target_module_id' => 'nullable|integer|exists:apiura_modules,id',
            'request_ids' => 'nullable|array',
            'request_ids.*' => 'integer|exists:saved_api_requests,id',
            'flow_ids' => 'nullable|array',
            'flow_ids.*' => 'integer|exists:saved_api_flows,id',
        ]);

        $targetModuleId = $request->input('target_module_id');
        $moved = 0;

        if ($request->has('request_ids')) {
            $moved += SavedApiRequest::whereIn('id', $request->input('request_ids'))
                ->update(['module_id' => $targetModuleId]);
        }

        if ($request->has('flow_ids')) {
            $moved += SavedApiFlow::whereIn('id', $request->input('flow_ids'))
                ->update(['module_id' => $targetModuleId]);
        }

        return response()->json([
            'message' => $moved.' item(s) moved.',
            'count' => $moved,
        ]);
    }

    public function importPreview(Request $request, ModuleDuplicateDetectionService $service): JsonResponse
    {
        $request->validate([
            'module_id' => 'nullable|integer|exists:apiura_modules,id',
            'requests' => 'nullable|array',
            'flows' => 'nullable|array',
        ]);

        $moduleId = $request->input('module_id');
        $result = ['requests' => [], 'flows' => []];

        if ($request->has('requests')) {
            $result['requests'] = $service->checkRequestDuplicates($moduleId, $request->input('requests'));
        }

        if ($request->has('flows')) {
            $result['flows'] = $service->checkFlowDuplicates($moduleId, $request->input('flows'));
        }

        return response()->json($result);
    }

    public function importExecute(Request $request): JsonResponse
    {
        $request->validate([
            'module_id' => 'nullable|integer|exists:apiura_modules,id',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|string|in:request,flow',
            'items.*.action' => 'required|string|in:import,overwrite,skip,copy',
            'items.*.data' => 'required|array',
            'items.*.overwrite_id' => 'nullable|integer',
        ]);

        $moduleId = $request->input('module_id');
        $counts = ['imported' => 0, 'overwritten' => 0, 'skipped' => 0, 'copied' => 0];

        foreach ($request->input('items') as $item) {
            $action = $item['action'];
            $data = $item['data'];
            $type = $item['type'];

            if ($action === 'skip') {
                $counts['skipped']++;
                continue;
            }

            if ($type === 'flow') {
                $flowData = $this->normalizeFlowData($data, $moduleId);

                if ($action === 'overwrite' && ! empty($item['overwrite_id'])) {
                    $existing = SavedApiFlow::find($item['overwrite_id']);
                    if ($existing) {
                        $existing->update($flowData);
                        $counts['overwritten']++;
                    }
                } elseif ($action === 'copy') {
                    $flowData['name'] = ($flowData['name'] ?? 'Untitled Flow').' (Copy)';
                    SavedApiFlow::create($flowData);
                    $counts['copied']++;
                } else {
                    SavedApiFlow::create($flowData);
                    $counts['imported']++;
                }
            } elseif ($type === 'request') {
                $data['module_id'] = $moduleId;

                if ($action === 'overwrite' && ! empty($item['overwrite_id'])) {
                    $existing = SavedApiRequest::find($item['overwrite_id']);
                    if ($existing) {
                        $existing->update($data);
                        $counts['overwritten']++;
                    }
                } elseif ($action === 'copy') {
                    $data['name'] = ($data['name'] ?? 'Untitled Request').' (Copy)';
                    SavedApiRequest::create($data);
                    $counts['copied']++;
                } else {
                    SavedApiRequest::create($data);
                    $counts['imported']++;
                }
            }
        }

        $total = $counts['imported'] + $counts['overwritten'] + $counts['copied'];
        $parts = [];
        if ($counts['imported'] > 0) $parts[] = $counts['imported'].' new';
        if ($counts['overwritten'] > 0) $parts[] = $counts['overwritten'].' overwritten';
        if ($counts['copied'] > 0) $parts[] = $counts['copied'].' copied';
        if ($counts['skipped'] > 0) $parts[] = $counts['skipped'].' skipped';

        return response()->json([
            'message' => "Imported {$total} item(s): ".implode(', ', $parts),
            'counts' => $counts,
        ]);
    }

    private function normalizeFlowData(array $data, ?int $moduleId): array
    {
        $flowData = [
            'module_id' => $moduleId,
            'name' => $data['name'] ?? 'Untitled Flow',
            'description' => $data['description'] ?? null,
            'steps' => $data['steps'] ?? [],
        ];

        if (isset($data['defaultHeaders'])) {
            $flowData['default_headers'] = $data['defaultHeaders'];
        } elseif (isset($data['default_headers'])) {
            $flowData['default_headers'] = $data['default_headers'];
        }

        if (isset($data['continueOnError'])) {
            $flowData['continue_on_error'] = $data['continueOnError'];
        } elseif (isset($data['continue_on_error'])) {
            $flowData['continue_on_error'] = $data['continue_on_error'];
        }

        return $flowData;
    }
}
