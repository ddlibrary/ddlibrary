<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'website_name' => $this->faker->name,
            'website_slogan' => $this->faker->name,
            'website_email' => $this->faker->name,
        ];
    }
}
