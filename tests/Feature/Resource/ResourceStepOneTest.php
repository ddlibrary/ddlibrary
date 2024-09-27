<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResourceStepOneTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_resource_image_and_redirects()
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('s3');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('image.jpg', 600, 600);

        $response = $this->post('/en/resources/add/step1', $this->data(['image' => $file]));

        $response->assertRedirect('/resources/add/step2');

        // Assert that the file was stored on S3
        $fileName = auth()->user()->id . '_' . time() . '.jpg';
        Storage::disk('s3')->assertExists('resources/' . $fileName);

        // Assert session data
        $this->assertEquals('This is Title', session('resource1.title'));
        $this->assertEquals('This is Author', session('resource1.author'));
    }

    /** @test */
    public function validate_required_fields()
    {
        $this->refreshApplicationWithLocale('en');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/en/resources/add/step1', []);

        $response->assertSessionHasErrors(['title', 'image', 'language', 'abstract']);
    }

    /** @test */
    public function image_should_be_square_in_shape()
    {
        $this->refreshApplicationWithLocale('en');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('image.jpg', 600, 400);

        $response = $this->post('/en/resources/add/step1', $this->data(['image' => $file]));

        $response->assertSessionHasErrors('image');
        $this->assertEquals('The resource image must be square in shape.', session('errors')->get('image')[0]);
    }

    /** @test */
    public function it_can_upload_only_image_type()
    {
        $this->refreshApplicationWithLocale('en');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Test with a non-image file
        $file = UploadedFile::fake()->create('document.pdf', 100); // Fake PDF file

        $response = $this->post('/en/resources/add/step1', $this->data(['image' => $file]));

        $response->assertSessionHasErrors('image');
        $this->assertEquals('The image field must be a file of type: jpg, jpeg, png.', session('errors')->get('image')[0]);
    }

    /** @test */
    public function image_should_be_less_than_3_mb()
    {
        $this->refreshApplicationWithLocale('en');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Test with an image exceeding the maximum size
        $largeFile = UploadedFile::fake()->image('large_image.jpg')->size(3073); // 3MB file

        $response = $this->post('/en/resources/add/step1', $this->data(['image' => $largeFile]));

        $response->assertSessionHasErrors('image');
        $this->assertEquals('The image field must not be greater than 3072 kilobytes.', session('errors')->get('image')[0]);
    }

    /** @test */
    public function author_field_is_optional()
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('s3');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/en/resources/add/step1', $this->data(['author' => null]));

        $response->assertRedirect('/resources/add/step2');

        // Assert session data
        $this->assertEquals('This is Title', session('resource1.title'));
        $this->assertNull(session('resource1.author'));
    }

    /** @test */
    public function publisher_field_is_optional()
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('s3');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/en/resources/add/step1', $this->data(['publisher' => null]));

        $response->assertRedirect('/resources/add/step2');

        // Assert session data
        $this->assertEquals('This is Title', session('resource1.title'));
        $this->assertNull(session('resource1.publisher'));
    }

    /** @test */
    public function translator_field_is_optional()
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('s3');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/en/resources/add/step1', $this->data(['translator' => null]));

        $response->assertRedirect('/resources/add/step2');

        // Assert session data
        $this->assertEquals('This is Title', session('resource1.title'));
        $this->assertNull(session('resource1.translator'));
    }

    /** @test */
    public function title_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/en/resources/add/step1', $this->data(['title' => '']));

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function language_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/en/resources/add/step1', $this->data(['language' => '']));

        $response->assertSessionHasErrors('language');
    }

    /** @test */
    public function abstract_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/en/resources/add/step1', $this->data(['abstract' => '']));

        $response->assertSessionHasErrors('abstract');
    }

    protected function data($merge = [])
    {
        $file = UploadedFile::fake()->image('image.jpg', 200, 200);
        return array_merge(
            [
                'title' => 'This is Title',
                'image' => $file,
                'author' => 'This is Author',
                'publisher' => 'This is Publisher',
                'translator' => 'This is Translator',
                'language' => 'en',
                'abstract' => 'This is an abstract.',
            ],
            $merge,
        );
    }
}
