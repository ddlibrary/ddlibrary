<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ResourceView;

class ResourceViewFactory extends Factory
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
            'user_id' => \App\Models\User::factory(),
            'ip' => $this->faker->word(),
        ];
    }
}
