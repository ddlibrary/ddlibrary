<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $resources = DB::table('resources')->select('id', 'abstract')->get();
        $baseUrl = 'https://library.darakhtdanesh.org/';
        foreach ($resources as $resource) {
            $defaultImage = $baseUrl . 'storage/files/placeholder_image.png';
            // Extract the image source using regex
            preg_match('/src=["\']([^"\']+)["\']/', $resource->abstract, $matches);

            if (!empty($matches[1])) {
                $absStr = $matches[1];

                // Skip if the URL contains 'youtube'
                if (strpos($absStr, 'youtube') === false) {
                    $imageName = basename($absStr); // Get the image name from the URL

                    if ($imageName) {
                        $defaultImage = $baseUrl . Storage::disk('public')->url($imageName);
                    }
                }
            }

            DB::table('resources')
                ->where('id', $resource->id)
                ->update(['image' => $defaultImage]);
        }
    }
}
