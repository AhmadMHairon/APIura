<?php

namespace Apiura\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavedApiRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module_id' => $this->module_id,
            'name' => $this->name,
            'priority' => $this->priority,
            'team' => $this->team,
            'method' => $this->method,
            'path' => $this->path,
            'path_params' => $this->path_params,
            'query_params' => $this->query_params,
            'headers' => $this->headers,
            'body' => $this->body,
            'response_status' => $this->response_status,
            'response_headers' => $this->when(! $this->isCollectionRequest($request), $this->response_headers),
            'response_body' => $this->when(! $this->isCollectionRequest($request), $this->response_body),
            'comments_count' => $this->when($this->comments_count !== null, $this->comments_count),
            'highest_comment_status' => $this->when($this->highest_comment_status !== null, $this->highest_comment_status),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function isCollectionRequest(Request $request): bool
    {
        return $request->routeIs('apiura.saved-requests.index');
    }
}
