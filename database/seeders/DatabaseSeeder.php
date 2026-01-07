<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(TaxonomyTermDataSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(GlossarySubjectsSeeder::class);
        $this->call(SubjectAreaSeeder::class);
        $this->call(LearningResourceTypeSeeder::class);
        $this->call(EducationUseSeeder::class);
        $this->call(ResourceLevelSeeder::class);
    }
}
