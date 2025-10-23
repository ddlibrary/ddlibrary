<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Resource;

class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->title,
            'abstract' => $this->faker->text(),
            'language' => 'en',
            'status' => 1,
            'tnid' => null,
            'title' => $this->faker->title,

        ];
    }
}
