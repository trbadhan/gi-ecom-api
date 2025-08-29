<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'sometimes|required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'order'     => 'nullable|integer',
            'is_active' => 'boolean',
        ];
    }
}
