<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
'email'    => 'email|required',
'password' => 'nullable|confirmed|string|min:8|regex:/^(?=.*[0-9])(?=.*[!@#$%^&.]).*$/',
'username' => 'required',
];
    }
}
