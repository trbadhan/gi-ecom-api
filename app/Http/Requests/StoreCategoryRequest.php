<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status'  => false,
                'code'    => 422,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
