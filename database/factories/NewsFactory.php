<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->name(),
            'summary' => $this->faker->paragraph(),
            'body' => $this->faker->paragraph(3),
            'language' => 'en',
            'tnid' => $this->faker->numberBetween(1, 200),
            'status' => $this->faker->numberBetween(1, 3),
            'user_id' => User::factory(),
        ];
    }
}
