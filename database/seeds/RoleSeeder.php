<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
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
                'id' => '1',
                'name' => 'anonymous user'
            ],
            [
                'id' => '2',
                'name' => 'authenticated user'
            ],
            [
                'id' => '3',
                'name' => 'library manager'
            ],
            [
                'id' => '4',
                'name' => 'library teacher'
            ],
            [
                'id' => '5',
                'name' => 'site administrator'
            ],
            [
                'id' => '6',
                'name' => 'library user'
            ]
        ];

        DB::table('roles')->insert($data);
    }
}
