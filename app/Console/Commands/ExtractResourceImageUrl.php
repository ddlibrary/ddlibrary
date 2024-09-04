<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExtractResourceImageUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:extract:image-url';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract image URLs from stract field and store in image field';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $resources = DB::table('resources')->select('id','abstract','image')->get();

        foreach ($resources as $resource) {
            // Use regex to extract the image src
            preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $resource->abstract, $matches);


            if (isset($matches[1])) {
                DB::table('resources')->where('id', $resource->id)->update(['image' => $matches[1]]);
            }
        }

        $this->info('Image URLs extracted and stored successfully.');
    }
}
