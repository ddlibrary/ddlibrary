<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DownloadCountFactory extends Factory
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
            'file_id' => \App\Models\ResourceAttachment::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
