<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
            ],
            'password' => [
                'nullable',
            ],
            'email' => [
                'required_without:phone',
                'nullable',
            ],
            'status' => [
                'required',
            ],
            'first_name' => [
                'required',
            ],
            'last_name' => [
                'required',
            ],
            'gender' => [
                'required',
            ],
            'role' => [
                'required',
            ],
            'phone' => [
                'required_without:email',
                'nullable',
            ],
            'country' => [
                'required',
            ],
            'city' => [
                'nullable',
            ],
        ];
    }
}
