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
    protected $description = 'Extract image URLs from stract field and store in image field';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $resources = Resource::select('id', 'abstract', 'title', 'image', 'language')->get();
        $baseUrl = 'https://library.darakhtdanesh.org';
        foreach ($resources as $resource) {
            $defaultImage = $baseUrl . '/storage/files/placeholder_image.png';
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

            $width = null;
            $height = null;
            $size = null;
            if (filter_var($defaultImage, FILTER_VALIDATE_URL)) {
                // Get the size of the image
                $imageSize = getimagesize($defaultImage);

                if ($imageSize) {
                    $width = $imageSize[0];
                    $height = $imageSize[1];
                    $size = $imageSize['mime'];
                }
            }
            $resourceFile = ResourceFile::create([
                'name' => $resource->title ? $resource->title : 'ٔno title',
                'path' => $defaultImage,
                'width' => $width,
                'height' => $height,
                'size' => $size,
                'language' => $resource->language,
                'thumbnail_path' => $defaultImage,
            ]);

            $resource->update(['image' => $defaultImage, 'resource_file_id' => $resourceFile->id]);
        }
    }
}
