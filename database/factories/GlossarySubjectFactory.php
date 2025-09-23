<?php

namespace Database\Factories;

use App\Models\GlossarySubject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GlossarySubject>
 */
class GlossarySubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GlossarySubject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'en' => $this->faker->name,
            'fa' => $this->faker->name,
            'ps' => $this->faker->name,
            'pa' => $this->faker->name,
            'mj' => $this->faker->name,
            'no' => $this->faker->name,
            'sh' => $this->faker->name,
            'sw' => $this->faker->name,
            'uz' => $this->faker->name,
        ];
    }
}
