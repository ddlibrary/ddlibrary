<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LearningResourceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'vid' => '7',
                'name' => 'Articles',
                'weight' => '0',
                'language' => 'en',
            ],
            [
                'vid' => '7',
                'name' => 'Books',
                'weight' => '0',
                'language' => 'en',
            ],
        ];

        DB::table('taxonomy_term_data')->insert($data);
    }
}
