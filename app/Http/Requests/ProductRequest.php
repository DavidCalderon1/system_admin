<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        if (!empty($this->warehouses_quantity)) {
            $this->merge(['warehouses_quantity' => json_decode($this->warehouses_quantity, true)]);
        }
        $unique = (!empty($this->id) && $this->id > 0) ? 'unique:products,code,' . $this->id : 'unique:products';

        $rulesImage = (is_file($this->image)) ? ['image', 'mimes:jpeg,png,jpg,gif,svg|max:2048', 'nullable'] : ['url', 'nullable'];

        return [
            "category_id" => ['numeric', 'min:1', 'required'],
            "code" => ['numeric', 'digits_between:6,6', 'required', $unique],
            "reference" => ['string', 'required'],
            "base_price" => ['numeric', 'min:0', 'required'],
            "vat" => ['numeric', 'required'],
            "price" => ['numeric', 'min:0', 'required'],
            "warehouses_quantity" => ['string'],
            "description" => ['string', 'nullable'],
            "image" => $rulesImage,
        ];
    }

    public function messages()
    {
        return [
            'category_id.numeric' => 'Este campo es requerido.',
            'code.digits_between' => 'Este campo debe contener 6 dígitos.'
        ];
    }
}
