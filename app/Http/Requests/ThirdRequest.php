<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThirdRequest extends FormRequest
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
            "identity_type" => ['required', 'in:CC,NIT'],
            "identity_number" => ['required', 'integer'],
            "type_person" => ['required', 'in:natural,juridical'],
            "type" => ['required', 'in:client,provider,other'],
            "name" => ['required'],
            "address" => ['required'],
            "country_code" => ['required'],
            "state_id" => ['required', 'integer', 'min:1'],
            "city_id" => ['required', 'integer', 'min:1'],
            "phone_number" => ['required'],
            "phone_extension" => ['integer', 'digits_between:1,5'],
            "email" => ['required', 'email'],
            "description" => ['required'],
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
