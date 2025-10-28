<?php

namespace App\Console\Commands;

use App\Models\Resource;
use App\Models\ResourceFile;
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
    protected $description = 'Extract image URLs from the abstract field and store in the image field.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $resources = Resource::select('id', 'abstract', 'title', 'language')->get();
        $baseUrl = config('app.url', 'https://library.darakhtdanesh.org');;
        
        foreach ($resources as $resource) {
            $defaultImage = $baseUrl . Storage::get('files/placeholder_image.png');
            preg_match('/src=["\']([^"\']+)["\']/', $resource->abstract, $matches);

            if (!empty($matches[1])) {
                $absStr = $matches[1];

                // Replace the old URL if found
                $absStr = str_replace('https://darakhtdanesh.org/public', $baseUrl, $absStr);

                // Skip if the URL contains 'youtube'
                if (strpos($absStr, 'youtube') === false) {
                    // Check if the image URL is relative or not
                    if (strpos($absStr, 'http') !== 0) {
                        // It's a relative URL, prepend the base URL
                        $imageName = basename($absStr); // Get the image name from the URL

                        if ($imageName) {
                            $defaultImage = $baseUrl . Storage::disk('public')->url($imageName);
                        }
                    } else {
                        // If it starts with 'http', use it directly
                        $defaultImage = $absStr;
                    }
                }
            }

            $resourceFile = ResourceFile::create([
                'label' => $resource->title ? $resource->title : 'no title',
                'name' => $defaultImage,
                'language' => $resource->language,
                'resource_id' => $resource->id
            ]);

            $resource->update(['resource_file_id' => $resourceFile->id]);
        }
    }
}
