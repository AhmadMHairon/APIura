<?php

namespace Apiura\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSavedApiRequestCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|max:5000',
            'author_type' => ['required', Rule::in(['backend', 'frontend', 'qa', 'other'])],
            'user_id' => 'nullable|exists:users,id',
            'author_name' => 'nullable|string|max:255',
            'status' => ['sometimes', Rule::in(['critical', 'warning', 'info', 'resolved'])],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (empty($this->user_id) && empty($this->author_name)) {
                $validator->errors()->add(
                    'author_name',
                    'Author name is required when no user is specified.'
                );
            }
        });
    }
}
