<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendResetLinkEmailForgotPasswordRequest;
use App\Rules\RecaptchaRule;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @throws ValidationException
     */
    protected function validateEmail(SendResetLinkEmailForgotPasswordRequest $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'g-recaptcha-response' => [config('settings.captcha') && config('settings.captcha') == 'no' ? 'nullable' : 'required', new RecaptchaRule],
        ]);
    }
}
