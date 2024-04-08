<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationUseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'vid' => '25',
                'name' => 'Assessment',
                'weight' => '0',
                'language' => 'en',
            ],
            [
                'vid' => '25',
                'name' => 'Curriculum and Instruction',
                'weight' => '0',
                'language' => 'en',
            ],
            [
                'vid' => '25',
                'name' => 'Informal Education',
                'weight' => '0',
                'language' => 'en',
            ],
            [
                'vid' => '25',
                'name' => 'Professional Development',
                'weight' => '0',
                'language' => 'en',
            ],
        ];

        DB::table('taxonomy_term_data')->insert($data);
    }
}
