<?php

namespace Apiura\Http\Controllers;

use Apiura\Http\Requests\StoreSavedApiRequestRequest;
use Apiura\Http\Requests\UpdateSavedApiRequestRequest;
use Apiura\Http\Resources\SavedApiRequestResource;
use Apiura\Models\SavedApiRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SavedApiRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $highestStatusSubquery = DB::raw("(
            SELECT CASE
                WHEN EXISTS (SELECT 1 FROM saved_api_request_comments WHERE saved_api_request_comments.saved_api_request_id = saved_api_requests.id AND saved_api_request_comments.status = 'critical') THEN 'critical'
                WHEN EXISTS (SELECT 1 FROM saved_api_request_comments WHERE saved_api_request_comments.saved_api_request_id = saved_api_requests.id AND saved_api_request_comments.status = 'warning') THEN 'warning'
                WHEN EXISTS (SELECT 1 FROM saved_api_request_comments WHERE saved_api_request_comments.saved_api_request_id = saved_api_requests.id AND saved_api_request_comments.status = 'info') THEN 'info'
                ELSE NULL
            END
        ) as highest_comment_status");

        $query = SavedApiRequest::select([
                'id', 'module_id', 'name', 'priority', 'team', 'method', 'path',
                'path_params', 'query_params', 'headers', 'body',
                'response_status', 'created_at', 'updated_at',
            ])
            ->addSelect($highestStatusSubquery)
            ->withCount('comments');

        if ($request->has('module_id')) {
            $query->where('module_id', $request->input('module_id'));
        }

        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(config('apiura.per_page', 50));

        return SavedApiRequestResource::collection($requests)->response();
    }

    public function store(StoreSavedApiRequestRequest $request): JsonResponse
    {
        try {
            $savedRequest = SavedApiRequest::create($request->validated());

            return (new SavedApiRequestResource($savedRequest))
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to save request.',
            ], 500);
        }
    }

    public function show(SavedApiRequest $savedRequest): JsonResponse
    {
        return (new SavedApiRequestResource($savedRequest))->response();
    }

    public function update(UpdateSavedApiRequestRequest $request, SavedApiRequest $savedRequest): JsonResponse
    {
        try {
            $savedRequest->update($request->validated());

            return (new SavedApiRequestResource($savedRequest))->response();
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to update request.',
            ], 500);
        }
    }

    public function destroy(SavedApiRequest $savedRequest): JsonResponse
    {
        $savedRequest->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
