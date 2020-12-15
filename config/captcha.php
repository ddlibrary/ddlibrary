<?php

return [
    'captcha' => env('CAPTCHA'),
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'options' => [
        'timeout' => 2.0,
    ],
];
