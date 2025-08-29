<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // you can add role/permission checks later
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'order'     => 'nullable|integer',
            'is_active' => 'boolean',
        ];
    }
}
