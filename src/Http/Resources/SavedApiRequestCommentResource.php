<?php

namespace Apiura\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavedApiRequestCommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'saved_api_request_id' => $this->saved_api_request_id,
            'user_id' => $this->user_id,
            'author_name' => $this->display_name,
            'author_type' => $this->author_type,
            'content' => $this->content,
            'status' => $this->status ?? 'info',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
