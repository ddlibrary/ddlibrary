<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
'website_name'   => [
                'required',
            ],
'website_slogan' => [
                'required',
            ],
'website_email'  => [
                'required',
            ],
];
    }
}
