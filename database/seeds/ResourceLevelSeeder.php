<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'vid' => '13',
                'name' => 'Adult Education / Continuing Studies',
                'weight' => '0',
                'language' => 'en',
            ],
            [
                'vid' => '13',
                'name' => 'Any Level',
                'weight' => '0',
                'language' => 'en',
            ],
            [
                'vid' => '13',
                'name' => 'Literacy',
                'weight' => '0',
                'language' => 'en',
            ],
            [
                'vid' => '13',
                'name' => 'Preschool',
                'weight' => '0',
                'language' => 'en',
            ],
        ];

        DB::table('taxonomy_term_data')->insert($data);
    }
}
