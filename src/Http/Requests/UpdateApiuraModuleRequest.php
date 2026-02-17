<?php

namespace Apiura\Http\Requests;

use Apiura\Models\ApiuraModule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApiuraModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $moduleId = $this->route('module')->id;

        return [
            'parent_id' => 'nullable|integer|exists:apiura_modules,id',
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('apiura_modules')->where(function ($query) {
                    $parentId = $this->input('parent_id', $this->route('module')->parent_id);

                    return $query->where('parent_id', $parentId);
                })->ignore($moduleId),
            ],
            'description' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->has('parent_id') && $this->input('parent_id') !== null) {
                $module = $this->route('module');
                $newParentId = $this->input('parent_id');

                // Can't move into itself
                if ($newParentId == $module->id) {
                    $validator->errors()->add('parent_id', 'Cannot move a module into itself.');

                    return;
                }

                // Can't move into own descendant (circular reference)
                $target = ApiuraModule::find($newParentId);
                if ($target && $target->isDescendantOf($module->id)) {
                    $validator->errors()->add('parent_id', 'Cannot move a module into its own descendant.');
                }

                // Enforce max depth of 3 levels
                $targetDepth = $target ? $target->depth + 1 : 0;
                if ($targetDepth >= 3) {
                    $validator->errors()->add('parent_id', 'Maximum nesting depth of 3 levels exceeded.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A module with this name already exists in the same folder.',
        ];
    }
}
