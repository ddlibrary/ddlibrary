<?php

use App\Http\Resources\Menu;
use Illuminate\Database\Seeder;
use Database\Seeders\CitySeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\ResoucesSeeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\ResourcesSeeder;
use Database\Seeders\SubjectAreaSeeder;
use Database\Seeders\EducationUseSeeder;
use Database\Seeders\ResourceLevelSeeder;
use Database\Seeders\GlossarySubjectsSeeder;
use Database\Seeders\TaxonomyTermDataSeeder;
use Database\Seeders\LearningResourceTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(TaxonomyTermDataSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(GlossarySubjectsSeeder::class);
      //  $this->call(SubjectAreaSeeder::class);
        $this->call(LearningResourceTypeSeeder::class);
        $this->call(EducationUseSeeder::class);
        $this->call(ResourceLevelSeeder::class);
         
       // $this->call([ResourcesSeeder::class]);
    }
}
