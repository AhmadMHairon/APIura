<?php

namespace Apiura\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavedApiFlowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module_id' => $this->module_id,
            'name' => $this->name,
            'description' => $this->description,
            'steps' => $this->steps,
            'defaultHeaders' => $this->default_headers ?? [],
            'continueOnError' => (bool) $this->continue_on_error,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
