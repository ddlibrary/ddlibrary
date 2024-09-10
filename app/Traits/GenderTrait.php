<?php

namespace App\Traits;

trait GenderTrait
{
    public function genders(): array
    {
        return [
            'male' => 'Male',
            'female' => 'Female',
            'none' => 'None',
        ];
    }
}
