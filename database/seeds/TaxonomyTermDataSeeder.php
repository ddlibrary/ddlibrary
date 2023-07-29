<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxonomyTermDataSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'vid' => '6',
                'name' => 'Any level',
                'weight' => '-10',
                'language' => 'und',
            ],
            [
                'vid' => '7',
                'name' => 'Resource Type',
                'weight' => '-8',
                'language' => 'und',
            ],
            [
                'vid' => '8',
                'name' => 'Resource Subject',
                'weight' => '-7',
                'language' => 'und',
            ],
            [
                'vid' => '9',
                'name' => 'Resource Publisher \/ Author',
                'weight' => '-6',
                'language' => 'und',
            ],
            [
                'vid' => '10',
                'name' => 'Creative Commons',
                'weight' => '-5',
                'language' => 'und',
            ],
            [
                'vid' => '11',
                'name' => 'User Districts',
                'weight' => '-3',
                'language' => 'und',
            ],
            [
                'vid' => '12',
                'name' => 'User Provinces',
                'weight' => '-2',
                'language' => 'und',
            ],
            [
                'vid' => '13',
                'name' => 'Resource Levels',
                'weight' => '-9',
                'language' => 'und',
            ],
            [
                'vid' => '14',
                'name' => 'User Affiliations',
                'weight' => '-4',
                'language' => 'und',
            ],
            [
                'vid' => '15',
                'name' => 'User Country',
                'weight' => '-1',
                'language' => 'und',
            ],
            [
                'vid' => '16',
                'name' => 'User Gender',
                'weight' => '0',
                'language' => 'und',
            ],
            [
                'vid' => '17',
                'name' => 'User Subjects',
                'weight' => '0',
                'language' => 'und',
            ],
            [
                'vid' => '18',
                'name' => 'User Teaching Level',
                'weight' => '0',
                'language' => 'und',
            ],
            [
                'vid' => '19',
                'name' => 'User Who Do You Teach',
                'weight' => '0',
                'language' => 'und',
            ],
            [
                'vid' => '20',
                'name' => 'Primary User',
                'weight' => '0',
                'language' => 'und',
            ],
            [
                'vid' => '21',
                'name' => 'Featured Resource Collections',
                'weight' => '0',
                'language' => 'und',
            ],
            [
                'vid' => '22',
                'name' => 'Resource Translator',
                'weight' => '0',
                'language' => 'und',
            ],
            [
                'vid' => '23',
                'name' => 'Keywords',
                'weight' => '0',
                'language' => 'und',
            ],
            [
                'vid' => '24',
                'name' => 'Resource Author',
                'weight' => '0',
                'language' => 'und',
            ],
            [
                'vid' => '25',
                'name' => 'Educational Use',
                'weight' => '0',
                'language' => 'und',
            ],
            [
                'vid' => '26',
                'name' => 'Share Permission',
                'weight' => '0',
                'language' => 'und',
            ]
        ];

        DB::table('taxonomy_vocabulary')->insert($data);
    }
}
