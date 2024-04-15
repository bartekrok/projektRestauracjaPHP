<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SidesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'id_sides' => 'required',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'photo' => 'required|string|max:2048',
        ];
        
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation Error.',
            'data' => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'price.required' => 'A price is required',
            'image.required' => 'A image is required',
        ];
    }
}
