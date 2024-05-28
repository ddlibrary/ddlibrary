<?php

namespace Database\Factories;

use App\Enums\LanguageEnum;
use App\Models\TaxonomyTerm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaxonomyTerm>
 */
class TaxonomyTermFactory extends Factory
{
    protected $model = TaxonomyTerm::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vid' => $this->faker->numberBetween(6,26),
            'name' => $this->faker->name,
            'weight' => $this->faker->boolean,
            'language' => LanguageEnum::English->value,
            'tnid' => 0,
        ];
    }
}
