<?php

namespace App\Traits;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait LanguageTrait
{
    public function getLanguages(): array
    {
        return LaravelLocalization::getSupportedLocales();
    }
}
