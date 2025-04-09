<?php

namespace Tests\Feature\Resource;

use App\Models\ResourceFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResourceStepOneTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_resource_image_and_redirects()
    {
        $this->refreshApplicationWithLocale('en');
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $resourceFile = ResourceFile::create([
            'uuid' => Str::uuid(),
            'name' => 'new-file',
            'license' => 'license',
            'path' => 'https://file.com',
        ]);

        $response = $this->post('/en/resources/add/step1', $this->data(['image' => $resourceFile->uuid]));

        $response->assertRedirect('/resources/add/step2');

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
