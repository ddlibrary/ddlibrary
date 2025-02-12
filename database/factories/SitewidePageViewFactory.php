<?php

namespace Database\Factories;

use App\Models\Browser;
use App\Models\Device;
use App\Models\Platform;
use App\Models\SitewidePageView;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SitewidePageView>
 */
class SitewidePageViewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SitewidePageView::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page_url' => $this->faker->url,
            'user_agent' => $this->faker->userAgent,
            'browser' => $this->faker->word,
            'title' => $this->faker->sentence,
            'is_bot' => $this->faker->boolean,
            'language' => $this->faker->languageCode,
            'gender' => $this->faker->randomElement(['male', 'female', null]),
            'device_id' => Device::factory(), // Assuming you have a Device factory
            'platform_id' => Platform::factory(), // Assuming you have a Platform factory
            'browser_id' => Browser::factory(), // Assuming you have a Browser factory
            'user_id' => User::factory()->create(), // Assuming you have a User factory
        ];
    }
}
