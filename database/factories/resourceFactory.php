<?php

namespace Database\Factories;

use App\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class resourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

     protected $model = Resource::class;
    public function definition()
    {
        return [
            //

            'title' => $this->faker->name(),
            'abstract' => $this->faker->name(),
            'language' => 'en',
            'user_id' => 2,
            'created_at'=>now(),
            'updated_at'=>now()
        ];
    }
}
