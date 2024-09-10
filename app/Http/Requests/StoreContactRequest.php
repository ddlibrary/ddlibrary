<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
'name'                 => [
                'required',
            ],
'email'                => [
                'required',
                'email',
            ],
'subject'              => [
                'required',
            ],
'message'              => [
                'required',
            ],
'g-recaptcha-response' => [
'required',
new RecaptchaRule(),
],
];
    }
}
