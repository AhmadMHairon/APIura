<?php

namespace Apiura\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSavedApiRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'module_id' => 'nullable|integer|exists:apiura_modules,id',
            'name' => 'nullable|string|max:255',
            'priority' => 'nullable|string|in:low,medium,high,critical',
            'team' => 'nullable|string|max:50',
            'method' => 'sometimes|required|string|max:10',
            'path' => 'sometimes|required|string|max:500',
            'path_params' => 'nullable|array',
            'query_params' => 'nullable|array',
            'headers' => 'nullable|array',
            'body' => 'nullable|array',
            'response_status' => 'nullable|integer',
            'response_headers' => 'nullable|array',
            'response_body' => 'nullable|string|max:1048576',
        ];
    }
}
