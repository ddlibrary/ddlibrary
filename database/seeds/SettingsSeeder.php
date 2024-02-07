<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
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
                'id' => '1',
                'website_name' => 'Darakht-e Danesh Library',
                'website_slogan' => 'Free and open educational resources for Afghanistan',
                'website_email' => 'support@darakhtdanesh.org',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('settings')->insert($data);
    }
}
