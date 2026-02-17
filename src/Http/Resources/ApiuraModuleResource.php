<?php

namespace Apiura\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiuraModuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'children' => self::collection($this->whenLoaded('children')),
            'saved_requests_count' => $this->when($this->saved_requests_count !== null, $this->saved_requests_count),
            'saved_flows_count' => $this->when($this->saved_flows_count !== null, $this->saved_flows_count),
            'saved_requests' => SavedApiRequestResource::collection($this->whenLoaded('savedRequests')),
            'saved_flows' => SavedApiFlowResource::collection($this->whenLoaded('savedFlows')),
            'ancestors' => $this->when($request->routeIs('apiura.modules.show'), function () {
                return collect($this->ancestors)->map(fn ($a) => ['id' => $a->id, 'name' => $a->name]);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
