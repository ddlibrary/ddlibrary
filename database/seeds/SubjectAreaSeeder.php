<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectAreaSeeder extends Seeder
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
                'vid' => '8',
                'name' => 'Afghan Media',
                'weight' => '0',
                'language' => 'en',
            ],
            [
                'vid' => '8',
                'name' => 'Computer Science',
                'weight' => '0',
                'language' => 'en',
            ],
        ];

        DB::table('taxonomy_term_data')->insert($data);
    }
}
