<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ResourceEducationalUse;

class ResourceEducationalUseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => \App\Models\Resource::factory(),
        ];
    }
}
