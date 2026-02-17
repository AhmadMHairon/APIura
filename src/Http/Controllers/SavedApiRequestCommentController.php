<?php

namespace Apiura\Http\Controllers;

use Apiura\Http\Requests\StoreSavedApiRequestCommentRequest;
use Apiura\Http\Resources\SavedApiRequestCommentResource;
use Apiura\Models\SavedApiRequest;
use Apiura\Models\SavedApiRequestComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

class SavedApiRequestCommentController extends Controller
{
    public function index(SavedApiRequest $savedRequest): JsonResponse
    {
        $comments = $savedRequest->comments()
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get();

        return SavedApiRequestCommentResource::collection($comments)->response();
    }

    public function store(StoreSavedApiRequestCommentRequest $request, SavedApiRequest $savedRequest): JsonResponse
    {
        $comment = $savedRequest->comments()->create([
            'user_id' => $request->validated('user_id'),
            'author_name' => $request->validated('author_name'),
            'author_type' => $request->validated('author_type'),
            'content' => $request->validated('content'),
            'status' => $request->validated('status', 'info'),
        ]);

        $comment->load('user:id,name');

        return (new SavedApiRequestCommentResource($comment))
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, SavedApiRequest $savedRequest, SavedApiRequestComment $comment): JsonResponse
    {
        if ($comment->saved_api_request_id !== $savedRequest->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['critical', 'warning', 'info', 'resolved'])],
        ]);

        $comment->update($validated);
        $comment->load('user:id,name');

        return (new SavedApiRequestCommentResource($comment))->response();
    }

    public function destroy(SavedApiRequest $savedRequest, SavedApiRequestComment $comment): JsonResponse
    {
        if ($comment->saved_api_request_id !== $savedRequest->id) {
            abort(404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
