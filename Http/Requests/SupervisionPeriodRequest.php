<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupervisionPeriodRequest extends FormRequest
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
            'id_supervision' => 'required',
            'id_foodtruck' => 'required',
            'id_employee' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
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
            'id_supervision.required' => 'An id supervision is required',
            'id_foodtruck.required' => 'An id foodtruck is required',
            'id_employee.required' => 'An id employee is required',
            'start_date.required' => 'A start date is required',
            'end_date.required' => 'An end date is required',
            ];
    }
}
