<?php

namespace Database\Factories;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
