<?php

use Illuminate\Support\Facades\Facade;

return [

    'captcha' => env('CAPTCHA', 'no'),

    'google_sso_enabled' => env('GOOGLE_SSO_ENABLED', false),

    'facebook_sso_enabled' => env('FACEBOOK_SSO_ENABLED', false),

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
    ])->toArray(),

];
