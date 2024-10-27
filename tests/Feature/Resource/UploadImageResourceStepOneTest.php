<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadImageResourceStepOneTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_resource_image()
    {
        Storage::fake('s3');
        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('image.jpg', 600, 600);

        $response = $this->post('upload-image', $this->data(['image' => $file]));

        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['imageUuid', 'imageUrl', 'imageName', 'message']);

        // Assert that the file was stored on S3
        $fileName = auth()->user()->id . '_' . time() . '.jpg';
        Storage::disk('s3')->assertExists('resources/' . $fileName);
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

    protected function data($merge = [])
    {
        $file = UploadedFile::fake()->image('image.jpg', 200, 200);
        return array_merge(
            [
                'license' => 'This is Title',
                'image' => $file,
                'image_name' => 'This is image name',
            ],
            $merge,
        );
    }
}
