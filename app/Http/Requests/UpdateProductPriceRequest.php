<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // later you can add role/permission check
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'default_price' => 'required|numeric|min:0',
            'variant_prices' => 'nullable|array',
            'variant_prices.*.name' => 'required_with:variant_prices|string',
            'variant_prices.*.price' => 'required_with:variant_prices|numeric|min:0',
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
