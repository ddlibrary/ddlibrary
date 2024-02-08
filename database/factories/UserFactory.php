<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'username' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('secret'),
            'language' => 'en',
            'status' => $this->faker->numberBetween(1, 3),
            'remember_token' => str_random(10),
            'accessed_at' => now(),
        ];
    }
}
