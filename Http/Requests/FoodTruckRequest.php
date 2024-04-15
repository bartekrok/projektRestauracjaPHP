<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FoodTruckRequest extends FormRequest
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
            'id_foodtruck' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'name' => 'required',
            'id_supervisor' => 'required',
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
            'id_foodtruck.required' => 'An id foodtruck is required',
            'city.required' => 'A city is required',
            'postal_code.required' => 'A postal code is required',
            'name.required' => 'A name is required',
            'id_supervisor.required' => 'An id supervisor is required',
            ];
    }
}
