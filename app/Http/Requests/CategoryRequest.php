<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $unique = (!empty($this->id)) ? 'unique:inventory_categories,name,' . $this->id : 'unique:inventory_categories';

        return [
            'name' => ['required', 'string', $unique],
            'description' => ['string', 'required'],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'El nombre ingresado ya existe.'
        ];
    }
}
