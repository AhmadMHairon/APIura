<?php

namespace Apiura\Http\Controllers;

use Apiura\Http\Requests\StoreSavedApiFlowRequest;
use Apiura\Http\Requests\UpdateSavedApiFlowRequest;
use Apiura\Http\Resources\SavedApiFlowResource;
use Apiura\Models\SavedApiFlow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SavedApiFlowController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = SavedApiFlow::orderBy('created_at', 'desc');

        if ($request->has('module_id')) {
            $query->where('module_id', $request->input('module_id'));
        }

        $flows = $query->paginate(config('apiura.per_page', 50));

        return SavedApiFlowResource::collection($flows)->response();
    }

    public function store(StoreSavedApiFlowRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            if (isset($data['defaultHeaders'])) {
                $data['default_headers'] = $data['defaultHeaders'];
                unset($data['defaultHeaders']);
            }

            if (isset($data['continueOnError'])) {
                $data['continue_on_error'] = $data['continueOnError'];
                unset($data['continueOnError']);
            }

            $savedFlow = SavedApiFlow::create($data);

            return (new SavedApiFlowResource($savedFlow))
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to save flow.',
            ], 500);
        }
    }

    public function show(SavedApiFlow $flow): JsonResponse
    {
        return (new SavedApiFlowResource($flow))->response();
    }

    public function update(UpdateSavedApiFlowRequest $request, SavedApiFlow $flow): JsonResponse
    {
        try {
            $data = $request->validated();

            if (isset($data['defaultHeaders'])) {
                $data['default_headers'] = $data['defaultHeaders'];
                unset($data['defaultHeaders']);
            }

            if (isset($data['continueOnError'])) {
                $data['continue_on_error'] = $data['continueOnError'];
                unset($data['continueOnError']);
            }

            $flow->update($data);

            return (new SavedApiFlowResource($flow))->response();
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to update flow.',
            ], 500);
        }
    }

    public function destroy(SavedApiFlow $flow): JsonResponse
    {
        $flow->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    public function bulkStore(Request $request): JsonResponse
    {
        $request->validate([
            'flows' => 'required|array|min:1|max:50',
            'flows.*.name' => 'nullable|string|max:255',
            'flows.*.description' => 'nullable|string|max:5000',
            'flows.*.steps' => 'present|array',
            'flows.*.steps.*.name' => 'required|string|max:255',
            'flows.*.steps.*.endpoint' => 'required|array',
            'flows.*.steps.*.endpoint.method' => 'required|string|max:10',
            'flows.*.steps.*.endpoint.path' => 'required|string|max:500',
            'flows.*.defaultHeaders' => 'nullable|array',
            'flows.*.continueOnError' => 'nullable|boolean',
        ]);

        $created = [];

        foreach ($request->input('flows') as $flowData) {
            if (isset($flowData['defaultHeaders'])) {
                $flowData['default_headers'] = $flowData['defaultHeaders'];
                unset($flowData['defaultHeaders']);
            }
            if (isset($flowData['continueOnError'])) {
                $flowData['continue_on_error'] = $flowData['continueOnError'];
                unset($flowData['continueOnError']);
            }
            if (empty($flowData['name'])) {
                $flowData['name'] = 'Untitled Flow';
            }

            $created[] = SavedApiFlow::create($flowData);
        }

        return response()->json([
            'message' => count($created) . ' flow(s) imported.',
            'count' => count($created),
        ], 201);
    }

    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:saved_api_flows,id',
        ]);

        $deleted = SavedApiFlow::whereIn('id', $request->input('ids'))->delete();

        return response()->json([
            'message' => $deleted . ' flow(s) deleted.',
            'count' => $deleted,
        ]);
    }
}
