<?php

namespace Database\Factories;

use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurveyQuestionOption>
 */
class SurveyQuestionOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => $this->faker->text(),
            'question_id' => SurveyQuestion::factory()->create(),
            'language' => 'en',
            'tnid' => null,
        ];
    }
}
