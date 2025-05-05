<?php

namespace Database\Factories;

use App\Models\TaxonomyHierarchy;
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
        return [
            'id' => (int)(TaxonomyHierarchy::latest()->value('id') + 1),
            'tid' => TaxonomyTerm::factory()->create()->id,
            'parent' => 1,
            'aux_id' => TaxonomyTerm::factory()->create()->id,
        ];
    }
}
