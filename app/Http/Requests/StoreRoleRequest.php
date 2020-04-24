<?php

namespace App\Http\Requests;

use App\Constants\PermissionsConstants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can(PermissionsConstants::ROLE_CREATE)
            || Auth::user()->can(PermissionsConstants::ROLE_UPDATE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $unique = (!empty($this->id)) ? 'unique:roles,id,' . $this->id : 'unique:roles';

        return [
            'name' => ['required', 'string', 'max:255', $unique],
            'slug' => ['required', 'string', 'max:255', $unique],
            'permissions' => ['array']
        ];
    }
}
