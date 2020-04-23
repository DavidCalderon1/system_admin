<?php

namespace App\Http\Requests;

use App\Constants\PermissionsConstants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can(PermissionsConstants::USER_CREATE)
            || Auth::user()->can(PermissionsConstants::USER_UPDATE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $unique = (!empty($this->id)) ? 'unique:users,id,' . $this->id : 'unique:users';

        $passwordRule = ((!empty($this->id) && !empty($this->password))
            || (empty($this->id) && !empty($this->password)))
            ? ['required', 'string', 'min:8', 'confirmed']
            : [];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', $unique],
            'password' => $passwordRule,
            'roles' => ['array'],
            'permissions' => ['array'],
        ];
    }
}
