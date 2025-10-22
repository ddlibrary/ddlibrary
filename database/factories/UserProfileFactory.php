<?php

namespace Database\Factories;

use App\Enums\TaxonomyVocabularyEnum;
use App\Models\TaxonomyTerm;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserProfile::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(),
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->lastName(),
            'gender' => "Male",
            'country' => TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::UserCountry->value]),
            'city' => TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::UserDistricts->value]),
            'phone' => $this->faker->numberBetween(10, 30000),
            'visited_storyweaver_disclaimer' => 1,
        ];
    }
}
