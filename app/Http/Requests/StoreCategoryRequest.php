<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;


class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pid = (int) $this->input('parent_id', 0);

        $rules = [
            'name'      => ['required','string','max:255'],
            'parent_id' => ['required','integer','gte:0'],
        ];

        if ($pid !== 0) {
            $rules['parent_id'][] = Rule::exists('categories', 'id');
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $pid = $this->input('parent_id');
        if ($pid === '' || is_null($pid)) {
            $this->merge(['parent_id' => 0]);
        }
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
