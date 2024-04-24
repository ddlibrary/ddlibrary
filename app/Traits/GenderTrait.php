<?php

namespace App\Traits;

use App\Models\DownloadCount;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait GenderTrait
{
    public function genders(): array
    {
        return [
            'Male' => 'Male',
            'Female' => 'Female',
            'None' => 'None'
        ];
    }
}
