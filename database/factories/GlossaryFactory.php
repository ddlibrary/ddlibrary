<?php

namespace Database\Factories;

use App\Models\Glossary;
use App\Models\GlossarySubject;
use Illuminate\Database\Eloquent\Factories\Factory;

class GlossaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Glossary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => GlossarySubject::factory()->create()->id,
            'name_en' => $this->faker->name(),
            'name_fa' => $this->faker->name(),
            'name_ps' => $this->faker->name(),
        ];
    }
}
