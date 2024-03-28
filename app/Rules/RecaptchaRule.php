<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $googleResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha_v3.secret_key'),
            'response' => $value,
            'remoteip' => request()->id,
        ]);

        $recaptchaResult = json_decode($googleResponse);

        if (! $recaptchaResult->success || (isset($recaptchaResult->score) && $recaptchaResult->score < 0.5)) {

            $fail('We have noticed some unusual usage patterns. Please try again later.');
        }
    }
}
