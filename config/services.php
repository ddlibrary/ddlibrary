<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => 'https://library.darakhtdanesh.org/login/facebook/callback/',
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT', 'https://library.darakhtdanesh.org/login/google/callback/'),
    ],

    'recaptcha_v3' => [
        'site_key' => env('RECAPTCHAV3_SITEKEY'),
        'secret_key' => env('RECAPTCHAV3_SECRET'),
    ],

    'cloudfront' => [
        'domain' => env('CLOUDFRONT_DOMAIN'),
        'key_pair_id' => env('CLOUDFRONT_KEY_PAIR_ID'),
        'private_key_path' => env('CLOUDFRONT_PRIVATE_KEY_PATH'),
    ],

    'matomo' => [
        'url' => env('MATOMO_URL'),
        'site_id' => env('MATOMO_SITE_ID'),
    ],
];
