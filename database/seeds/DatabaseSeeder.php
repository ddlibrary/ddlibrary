<?php

use Database\Seeders\CitySeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\EducationUseSeeder;
use Database\Seeders\GlossarySubjectsSeeder;
use Database\Seeders\LearningResourceTypeSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\ResourceLevelSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\SubjectAreaSeeder;
use Database\Seeders\TaxonomyTermDataSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
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
