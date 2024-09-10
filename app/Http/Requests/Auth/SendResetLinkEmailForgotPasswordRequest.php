<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendResetLinkEmailForgotPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
'email'                => [
                'required',
                'email',
            ],
'g-recaptcha-response' => [
config('settings.captcha') && config('settings.captcha') == 'no' ? 'nullable' : 'required',
new RecaptchaRule(),
],
];
    }
}
