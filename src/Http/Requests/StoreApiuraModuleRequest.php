<?php

namespace Apiura\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApiuraModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|integer|exists:apiura_modules,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('apiura_modules')->where(function ($query) {
                    return $query->where('parent_id', $this->input('parent_id'));
                }),
            ],
            'description' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A module with this name already exists in the same folder.',
        ];
    }
}
