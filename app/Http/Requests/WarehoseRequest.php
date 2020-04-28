<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehoseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ['required'],
            "address" => ['required'],
            "phone_number" => ['required', 'numeric', 'digits_between:7,10'],
            "country_code" => ['required'],
            "state_id" => ['required', 'integer', 'min:1'],
            "city_id" => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'state_id.min' => 'El campo Departamento es requerido.',
            'city_id.min' => 'El campo Ciudad es requerido.',
        ];
    }
}
