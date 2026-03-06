<?php

namespace App\Console\Commands;

use App\Models\Resource;
use App\Models\ResourceFile;
use Illuminate\Console\Command;

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
    public function handle(): void
    {
        $resources = Resource::select('id', 'abstract', 'title', 'language', 'resource_file_id')
            ->whereNull('resource_file_id')
            ->get();

        foreach ($resources as $resource) {
            // Add console output for the current resource
            $this->info('Processing resource: ID '.$resource->id.', Title: '.$resource->title);

            $defaultImage = 'placeholder_image.png';
            preg_match('/src=["\']([^"\']+)["\']/', $resource->abstract, $matches);

            if (! empty($matches[1])) {
                $absStr = $matches[1];

                // Replace the old URL if found
                $absStr = str_replace('https://darakhtdanesh.org/public', '', $absStr);

                // Skip if the URL contains 'youtube'
                if (strpos($absStr, 'youtube') === false) {
                    // Check if the image URL is relative or not
                    if (strpos($absStr, 'http') !== 0) {
                        // It's a relative URL, prepend the base URL
                        $imageName = basename($absStr);
                        if ($imageName) {
                            $defaultImage = $imageName;
                        }
                    } else {
                        // If it starts with 'http', use it directly
                        $defaultImage = $absStr;
                    }
                }
            }

            // Create the resource file
            $resourceFile = ResourceFile::create([
                'label' => $resource->title ?: 'no title',
                'name' => $this->replaceUrl($defaultImage),
                'language' => $resource->language,
                'resource_id' => $resource->id,
            ]);

            // Update the resource with the new resource_file_id
            $resource->update(['resource_file_id' => $resourceFile->id]);
        }
    }

    public function replaceUrl($url)
    {
        if (strpos($url, 'http://darakhtdanesh.org/modules/file/') !== false || strpos($url, 'C:\\Users') !== false) {
            return 'placeholder_image.png';
        }

        // Define the patterns to check against
        $patterns = [
            'https://www.darakhtdanesh.org/laravel-filemanager/app/public/files/',
            'https://darakhtdanesh.org/laravel-filemanager/app/public/files/',
            'https://www.darakhtdanesh.org/laravel-filemanager/app/public/',
            'https://darakhtdanesh.org/storage/files/././',
            'https://darakhtdanesh.org/storage/files/./',
            'https://darakhtdanesh.org/storage/files/',
            'https://library.darakhtdanesh.org/storage/files/./',
            'https://library.darakhtdanesh.org/storage/files/',
            'https://www.ddl.af/laravel-filemanager/app/public/files/',
            'https://ddl.af/laravel-filemanager/app/public/files/',
            'https://ddl.af/laravel-filemanager/app/public/',
            'https://darakhtdanesh.org/laravel-filemanager/app/public/',
            'https://library.darakhtdanesh.org/',
            'storage/files/',
            'https://darakhtdanesh.org/',
            'files/',
            '/',
        ];

        // Check if the URL contains any of the specified patterns
        foreach ($patterns as $pattern) {
            // Use rtrim to ensure we handle trailing slashes properly
            $pattern = rtrim($pattern, '/').'/'; // ensure pattern ends with a slash
            if (strpos($url, $pattern) !== false) {
                return str_replace($pattern, '', $url);
            }
        }

        return $url;
    }
}
