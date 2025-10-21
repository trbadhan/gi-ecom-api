<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'other_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'delete_other_images' => 'nullable|array',
            'delete_other_images.*' => 'integer|exists:product_images,id',
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
