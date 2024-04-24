<?php

namespace App\Traits;

use App\Models\DownloadCount;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait LanguageTrait
{
    public function getLanguages(): array
    {
        return LaravelLocalization::getSupportedLocales();
    }
}
