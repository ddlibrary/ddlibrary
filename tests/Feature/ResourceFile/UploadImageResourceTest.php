<?php

namespace Tests\Feature\ResourceFile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadImageResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_resource_image()
    {
        $fileSystemDisk = env('FILESYSTEM_DISK', 'local');
        
        Storage::fake($fileSystemDisk);
        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('image.jpg', 600, 600);

        $response = $this->post('upload-image', $this->data(['image' => $file]));

        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['imageUuid', 'imageUrl', 'imageName', 'message']);

        // Assert that the file was stored on S3
        $fileName = auth()->user()->id . '_' . time() . '.jpg';
        Storage::disk($fileSystemDisk)->assertExists('resources/' . $fileName);
    }

    /** @test */
    public function image_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('upload-image', []);

        $response->assertJson([
            'success' => false,
            'errors' => [
                'image' => [
                    'The image field is required.'
                ]
            ]
        ]);
    }

    /** @test */
    public function image_should_be_square_in_shape()
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('image.jpg', 600, 400);

        $response = $this->post('upload-image', $this->data(['image' => $file]));

        $response->assertJson([
            'success' => false,
            'errors' => [
                'image' => [
                    'The resource image must be square in shape.'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_can_upload_only_image_type()
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test with a non-image file
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->post('upload-image', $this->data(['image' => $file]));

        // Check for JSON response indicating failure
        $response->assertJson([
            'success' => false,
            'errors' => [
                'image' => [
                    'The image field must be a file of type: jpg, jpeg, png.',
                    'The image field must be an image.',
                    'The resource image must be square in shape.'
                ]
            ]
        ]);
    }

    /** @test */
    public function image_should_be_less_than_3_mb()
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();
        $this->actingAs($user);

        $largeFile = UploadedFile::fake()->image('large_image.jpg')->size(3073); // 3MB file

        $response = $this->post('upload-image', $this->data(['image' => $largeFile]));

        $response->assertJson([
            'success' => false,
            'errors' => [
                'image' => [
                    'The image field must not be greater than 3072 kilobytes.'
                ]
            ]
        ]);
    }

    protected function data($merge = [])
    {
        $file = UploadedFile::fake()->image('image.jpg', 200, 200);
        return array_merge(
            [
                'image' => $file,
                'license' => 'This is Title',
                'image_name' => 'This is image name',
            ],
            $merge,
        );
    }
}
