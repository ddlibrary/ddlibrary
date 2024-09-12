<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ResourceCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory()->create(),
            'user_id' => User::factory()->create(),
            'comment' => $this->faker->text(),
            'status' => 1
        ];
    }
}
