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
        $resources = Resource::select('id', 'abstract', 'title', 'language', 'resource_file_id')->whereNull('resource_file_id')->get();

        $baseUrl = config('app.url', 'https://library.darakhtdanesh.org');

        foreach ($resources as $resource) {
            // Add console output for the current resource
            $this->info('Processing resource: ID ' . $resource->id . ', Title: ' . $resource->title);

            $defaultImage = 'placeholder_image.png';
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
                        $imageName = basename($absStr);
                        if ($imageName) {
                            $defaultImage = $baseUrl . Storage::disk('public')->url($imageName);
                        }
                    } else {
                        // If it starts with 'http', use it directly
                        $defaultImage = $absStr;
                    }
                }
            }

            // Ensure the default image uses the correct base URL
            if (!strpos($defaultImage, 'darakhtdanesh.org')) {
                $defaultImage = "https://library.darakhtdanesh.org/$defaultImage";
            } else {
                $defaultImage = $this->replaceUrl($defaultImage);
            }

            // Get image dimensions using Imagine
            $width = null;
            $height = null;

            $imagine = new Imagine();
            try {
                $image = $imagine->open($defaultImage);
                $size = $image->getSize();
                $width = $size->getWidth();
                $height = $size->getHeight();
            } catch (\Throwable $e) {
                // If the image cannot be opened, use the default image
                try {
                    $image = $imagine->open($baseUrl . Storage::url('files/placeholder_image.png'));
                    $size = $image->getSize();
                    $width = $size->getWidth();
                    $height = $size->getHeight();
                    $defaultImage = 'placeholder_image.png';
                    $this->info('Getting error with resource: ID ' . $resource->id . ', Title: ' . $resource->title. ' '.$e. ' But do not worry we are using default placeholder image');
                } catch (\Throwable $e) {
                    // If the default image cannot be opened, skip this resource
                    $this->info('Default image also could not be opened for resource ID: ' . $resource->id . ' ' . $e);

                    continue;
                }
            }

            // Create the resource file
            
            $resourceFile = ResourceFile::create([
                'label' => $resource->title ?: 'no title',
                'name' => $this->getFileName($defaultImage),
                'language' => $resource->language,
                'resource_id' => $resource->id,
                'width' => $width,
                'height' => $height,
            ]);

            // Update the resource with the new resource_file_id
            $resource->update(['resource_file_id' => $resourceFile->id]);
        }
    }

    function replaceUrl($url)
    {
        // Define the patterns to check against
        $patterns = ['https://www.darakhtdanesh.org/laravel-filemanager/app/public', 'https://darakhtdanesh.org/laravel-filemanager/app/public', ''];

        // Check if the URL contains any of the specified substrings
        foreach ($patterns as $pattern) {
            if (strpos($url, $pattern) !== false) {
                return str_replace($pattern, '', $url);
            }
        }

        return $url;
    }

    function getFileName($url){
        return str_replace("https://library.darakhtdanesh.org/storage/files/", '', $url);
    }
}
