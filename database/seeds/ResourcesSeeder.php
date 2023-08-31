<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use app\Resource;
class ResourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
 
        $resource = new Resource;

        $resource-> id = 1;
        $resource-> title= 'new resource';
        $resource-> abstract = 'abstract2222';
        $resource-> language = 'en';
        $resource-> user_id = 2;
        $resource-> created_at= now();
        $resource->updated_at = now();

        $resource->save();

          
    }
}
