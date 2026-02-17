<?php

namespace Apiura\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSavedApiFlowRequest extends FormRequest
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
            'description' => 'nullable|string|max:5000',
            'steps' => 'present|array',
            'steps.*.id' => 'nullable',
            'steps.*.name' => 'required|string|max:255',
            'steps.*.endpoint' => 'required|array',
            'steps.*.endpoint.method' => 'required|string|max:10',
            'steps.*.endpoint.path' => 'required|string|max:500',
            'steps.*.pathParams' => 'nullable|array',
            'steps.*.params' => 'nullable|array',
            'steps.*.headers' => 'nullable|array',
            'steps.*.body' => 'nullable',
            'steps.*.bodyJson' => 'nullable|string',
            'steps.*.bodyMode' => 'nullable|string|in:form,json,formdata,urlencoded,raw',
            'steps.*.formDataEntries' => 'nullable|array',
            'steps.*.formDataEntries.*.key' => 'nullable|string',
            'steps.*.formDataEntries.*.value' => 'nullable|string',
            'steps.*.formDataEntries.*.type' => 'nullable|string|in:text,file',
            'steps.*.urlencodedBody' => 'nullable|array',
            'steps.*.rawBody' => 'nullable|string',
            'steps.*.rawContentType' => 'nullable|string|max:100',
            'steps.*.extractVariables' => 'nullable|array',
            'steps.*.assertions' => 'nullable|array',
            'steps.*.expectedResult' => 'nullable|string',
            'steps.*.expectedStatus' => 'nullable',
            'defaultHeaders' => 'nullable|array',
            'continueOnError' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->name)) {
            $this->merge(['name' => 'Untitled Flow']);
        }
    }
}
