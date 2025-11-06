<?php

namespace App\Console\Commands;

use App\Models\Resource;
use App\Models\ResourceFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Imagine\Gd\Imagine;

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
        $resources = Resource::whereNull('resource_file_id')
            ->select('id', 'abstract', 'title', 'language', 'resource_file_id')
            ->get();
        $baseUrl = config('app.url', 'https://library.darakhtdanesh.org');

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

            // Get image dimensions using Imagine
            $width = null;
            $height = null;

            if (!strpos($defaultImage, 'library.darakhtdanesh.org')) {
              $defaultImage = "https://library.darakhtdanesh.org/$defaultImage";
            }

            $imagine = new Imagine();
                try {
                    $image = $imagine->open($defaultImage);
                    $size = $image->getSize();
                    $width = $size->getWidth();
                    $height = $size->getHeight();
                } catch (\Throwable $e) {
                    // If the image cannot be opened, skip this resource
                    continue;
                }

            $resourceFile = ResourceFile::create([
                'label' => $resource->title ? $resource->title : 'no title',
                'name' => $defaultImage,
                'language' => $resource->language,
                'resource_id' => $resource->id,
                'width' => $width,
                'height' => $height,
            ]);

            $resource->update(['resource_file_id' => $resourceFile->id]);
        }
    }

    function checkImageExists($url) {
    // Encode the URL to handle spaces and special characters
    $encodedUrl = str_replace(' ', '%20', $url);
    
    // Use get_headers to check if the image exists
    $headers = @get_headers($encodedUrl);
    
    // Check if the response is 200 OK
    if (is_array($headers) && strpos($headers[0], '200') !== false) {
        return true; // Image exists
    }

    return false; // Image does not exist
}
}
