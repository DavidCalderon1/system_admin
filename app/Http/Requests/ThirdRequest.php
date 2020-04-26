<?php

namespace App\Http\Requests;

use App\Constants\ThirdsConstants;
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
        $lastNameRequired =
            $this->get('type_person', '') == ThirdsConstants::VALUES_TYPE_PERSON['natural'] && empty($this->get('last_name'))
                ? 'required' : 'nullable';

        $identityTypeIn = $this->getIn(ThirdsConstants::VALUES_IDENTITY_TYPE);
        $typePersonIn = $this->getIn(ThirdsConstants::VALUES_TYPE_PERSON);
        $typeRegisterIn = $this->getIn(ThirdsConstants::VALUES_TYPE_REGISTER);

        $unique = (!empty($this->id)) ? 'unique:third_parties,identity_number,' . $this->id : 'unique:third_parties';

        return [
            "identity_type" => ['required', $identityTypeIn],
            "identity_number" => ['required', 'integer', $unique],
            "type_person" => ['required', $typePersonIn],
            "type" => ['required', $typeRegisterIn],
            "name" => ['required'],
            "last_name" => ['string', $lastNameRequired],
            "address" => ['required'],
            "country_code" => ['required'],
            "state_id" => ['required', 'integer', 'min:1'],
            "city_id" => ['required', 'integer', 'min:1'],
            "phone_number" => ['required', 'numeric', 'digits_between:7,10'],
            "phone_extension" => ['integer', 'digits_between:1,5', 'nullable'],
            "email" => ['required', 'email'],
            "description" => ['string', 'nullable'],
        ];
    }

    /**
     * @param array $constValues
     * @return string
     */
    private function getIn(array $constValues)
    {
        $in = 'in:';
        foreach ($constValues as $constValue) {
            $in .= $constValue . ',';
        }

        return trim($in, ',');
    }

    public function messages()
    {
        return [
            'state_id.min' => 'El campo Departamento es requerido.',
            'city_id.min' => 'El campo Ciudad es requerido.',
        ];
    }
}
