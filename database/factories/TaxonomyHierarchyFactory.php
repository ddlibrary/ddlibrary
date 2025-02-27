<?php

namespace Database\Factories;

use App\Models\TaxonomyTerm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaxonomyHierarchy>
 */
class TaxonomyHierarchyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $id = TaxonomyTerm::factory()->create()->id;
        return [
            'tid' => $id,
            'parent' => 1,
            'aux_id' => $id,
        ];
    }
}
