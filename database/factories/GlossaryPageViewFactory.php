<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GlossaryPageViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_agent' => $this->faker->userAgent(),
            'browser' => $this->faker->word(),
            'is_bot' => $this->faker->boolean(),
            'language' => $this->faker->word(),
            'device_id' => \App\Models\Device::factory(),
            'platform_id' => \App\Models\Platform::factory(),
            'browser_id' => \App\Models\Browser::factory(),
            'status' => $this->faker->boolean(),
            'user_id' => \App\Models\User::factory(),
            'glossary_subject_id' => \App\Models\GlossarySubject::factory(),
        ];
    }
}
