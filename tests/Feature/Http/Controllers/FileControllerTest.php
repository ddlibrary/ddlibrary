<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FileController
 */
class FileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function invoke_aborts_with_a_404_for_non_existent_file(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get(url('storage/999/999/nonexistent.pdf'));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_returns_success_response_with_valid_image(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test-image.jpg', 100, 100);

        $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'url',
            'location',
        ]);

        $responseData = $response->json();
        $this->assertNotEmpty($responseData['url']);
        $this->assertNotEmpty($responseData['location']);
        $this->assertEquals($responseData['url'], $responseData['location']);

        // Verify file was stored - extract filename from URL
        $url = $responseData['url'];
        $fileName = basename(parse_url($url, PHP_URL_PATH));
        Storage::disk('public')->assertExists($fileName);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_stores_file_in_public_disk_in_non_production_environment(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('public');

        // Ensure we're not in production
        config(['app.env' => 'local']);

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test-image.png', 200, 200);

        $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        
        // Verify URL contains storage path
        $this->assertStringContainsString('storage', $responseData['url']);

        // Verify file was stored in public disk
        $files = Storage::disk('public')->allFiles();
        $this->assertNotEmpty($files);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_stores_file_in_s3_in_production_environment(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('s3');

        // Set to production environment
        $originalEnv = config('app.env');
        config(['app.env' => 'production']);

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test-image.gif', 150, 150);

        $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);

        // Verify file was stored in S3 disk
        $files = Storage::disk('s3')->allFiles();
        $this->assertNotEmpty($files);

        // Verify response contains S3 URL
        $responseData = $response->json();
        $this->assertNotEmpty($responseData['url']);
        $this->assertNotEmpty($responseData['location']);

        // Reset environment
        config(['app.env' => $originalEnv]);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_uses_s3_url_in_production(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('s3');

        // Set to production environment
        $originalEnv = config('app.env');
        config(['app.env' => 'production']);

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test-image.png', 200, 200);

        $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();

        // In production, S3 URL should be returned
        // Note: Storage::fake('s3') will return a fake URL, but the logic should use S3 disk
        $this->assertNotEmpty($responseData['url']);
        $this->assertNotEmpty($responseData['location']);

        // Verify file exists in S3 disk
        $files = Storage::disk('s3')->allFiles();
        $this->assertCount(1, $files);

        // Reset environment
        config(['app.env' => $originalEnv]);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_stores_different_files_in_s3(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('s3');

        // Set to production environment
        $originalEnv = config('app.env');
        config(['app.env' => 'production']);

        $user = User::factory()->create();

        // Upload multiple files
        $files = [
            UploadedFile::fake()->image('image1.jpg', 100, 100),
            UploadedFile::fake()->image('image2.png', 150, 150),
            UploadedFile::fake()->image('image3.gif', 200, 200),
        ];

        foreach ($files as $file) {
            $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
                'upload' => $file,
            ]);

            $response->assertStatus(200);
        }

        // Verify all files were stored in S3
        $s3Files = Storage::disk('s3')->allFiles();
        $this->assertCount(3, $s3Files);

        // Reset environment
        config(['app.env' => $originalEnv]);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_switches_between_public_and_s3_based_on_environment(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('public');
        Storage::fake('s3');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        // Test non-production (public disk)
        $originalEnv = config('app.env');
        config(['app.env' => 'local']);

        $response1 = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response1->assertStatus(200);
        $publicFiles = Storage::disk('public')->allFiles();
        $this->assertNotEmpty($publicFiles);

        // Test production (S3 disk)
        config(['app.env' => 'production']);

        $file2 = UploadedFile::fake()->image('test2.jpg', 100, 100);
        $response2 = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file2,
        ]);

        $response2->assertStatus(200);
        $s3Files = Storage::disk('s3')->allFiles();
        $this->assertNotEmpty($s3Files);

        // Reset environment
        config(['app.env' => $originalEnv]);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_s3_storage_creates_correct_filename(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('s3');

        // Set to production environment
        $originalEnv = config('app.env');
        config(['app.env' => 'production']);

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('original-name.jpg', 100, 100);

        $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);

        // Get stored files
        $s3Files = Storage::disk('s3')->allFiles();
        $this->assertCount(1, $s3Files);

        $storedFileName = $s3Files[0];

        // Filename should start with user ID
        $this->assertStringStartsWith((string) $user->id . '_', $storedFileName);

        // Filename should end with .jpg
        $this->assertStringEndsWith('.jpg', $storedFileName);

        // Filename should not contain original name (for security)
        $this->assertStringNotContainsString('original-name', $storedFileName);

        // Reset environment
        config(['app.env' => $originalEnv]);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_s3_url_format_is_correct(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('s3');

        // Set to production environment
        $originalEnv = config('app.env');
        config(['app.env' => 'production']);

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();

        // Both url and location should be present and equal
        $this->assertArrayHasKey('url', $responseData);
        $this->assertArrayHasKey('location', $responseData);
        $this->assertEquals($responseData['url'], $responseData['location']);

        // URLs should be strings
        $this->assertIsString($responseData['url']);
        $this->assertIsString($responseData['location']);

        // Reset environment
        config(['app.env' => $originalEnv]);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_s3_handles_all_valid_image_types(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('s3');

        // Set to production environment
        $originalEnv = config('app.env');
        config(['app.env' => 'production']);

        $user = User::factory()->create();

        $imageTypes = [
            'jpeg' => UploadedFile::fake()->image('test.jpeg', 100, 100),
            'jpg' => UploadedFile::fake()->image('test.jpg', 100, 100),
            'png' => UploadedFile::fake()->image('test.png', 100, 100),
            'gif' => UploadedFile::fake()->image('test.gif', 100, 100),
            'bmp' => UploadedFile::fake()->image('test.bmp', 100, 100),
        ];

        foreach ($imageTypes as $type => $file) {
            $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
                'upload' => $file,
            ]);

            $response->assertStatus(200);
            $responseData = $response->json();

            // Verify file was stored
            $s3Files = Storage::disk('s3')->allFiles();
            $this->assertNotEmpty($s3Files);

            // Verify filename has correct extension
            $storedFile = end($s3Files);
            $this->assertStringEndsWith('.' . $type, $storedFile);
        }

        // Reset environment
        config(['app.env' => $originalEnv]);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_validates_upload_field_is_required(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('upload.image.from.editor'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['upload']);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_validates_upload_must_be_an_image(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('upload.image.from.editor'), [
                'upload' => $file,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['upload']);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_validates_file_size_max_10mb(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        // Create a file larger than 10MB (10240 KB)
        $file = UploadedFile::fake()->image('large-image.jpg')->size(10241); // 10241 KB = > 10MB

        $response = $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('upload.image.from.editor'), [
                'upload' => $file,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['upload']);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_accepts_valid_image_formats(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('public');

        $user = User::factory()->create();

        $validFormats = [
            'jpeg' => UploadedFile::fake()->image('test.jpeg', 100, 100),
            'jpg' => UploadedFile::fake()->image('test.jpg', 100, 100),
            'png' => UploadedFile::fake()->image('test.png', 100, 100),
            'gif' => UploadedFile::fake()->image('test.gif', 100, 100),
            'bmp' => UploadedFile::fake()->image('test.bmp', 100, 100),
        ];

        foreach ($validFormats as $format => $file) {
            $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
                'upload' => $file,
            ]);

            $response->assertStatus(200);
            $response->assertJsonStructure([
                'url',
                'location',
            ]);
        }
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_rejects_invalid_mime_types(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();

        $invalidFiles = [
            UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            UploadedFile::fake()->create('video.mp4', 100, 'video/mp4'),
            UploadedFile::fake()->create('audio.mp3', 100, 'audio/mpeg'),
            UploadedFile::fake()->create('text.txt', 100, 'text/plain'),
        ];

        foreach ($invalidFiles as $file) {
            $response = $this->actingAs($user)
                ->withHeaders(['Accept' => 'application/json'])
                ->post(route('upload.image.from.editor'), [
                    'upload' => $file,
                ]);

            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['upload']);
        }
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_requires_authentication(): void
    {
        $this->refreshApplicationWithLocale('en');

        $file = UploadedFile::fake()->image('test-image.jpg', 100, 100);

        $response = $this->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_generates_unique_filename(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('public');

        $user = User::factory()->create();
        $file1 = UploadedFile::fake()->image('test1.jpg', 100, 100);
        $file2 = UploadedFile::fake()->image('test2.jpg', 100, 100);

        $response1 = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file1,
        ]);

        // Small delay to ensure different timestamp
        usleep(1000);

        $response2 = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file2,
        ]);

        $response1->assertStatus(200);
        $response2->assertStatus(200);

        $url1 = $response1->json('url');
        $url2 = $response2->json('url');

        // Filenames should be different
        $this->assertNotEquals($url1, $url2);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_filename_includes_user_id(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);
        $url = $response->json('url');

        // Extract filename from URL
        $fileName = basename(parse_url($url, PHP_URL_PATH));

        // Filename should start with user ID
        $this->assertStringStartsWith((string) $user->id . '_', $fileName);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_preserves_file_extension(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('public');

        $user = User::factory()->create();
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

        foreach ($extensions as $ext) {
            $file = UploadedFile::fake()->image("test.{$ext}", 100, 100);

            $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
                'upload' => $file,
            ]);

            $response->assertStatus(200);
            $url = $response->json('url');

            // Extract filename from URL
            $fileName = basename(parse_url($url, PHP_URL_PATH));

            // Filename should end with correct extension
            $this->assertStringEndsWith('.' . $ext, $fileName);
        }
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_returns_json_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        
        $responseData = $response->json();
        $this->assertIsString($responseData['url']);
        $this->assertNotEmpty($responseData['url']);
        $this->assertIsString($responseData['location']);
        $this->assertNotEmpty($responseData['location']);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_url_and_location_are_same(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals($responseData['url'], $responseData['location']);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_handles_large_valid_image(): void
    {
        $this->refreshApplicationWithLocale('en');
        Storage::fake('public');

        $user = User::factory()->create();
        // Create a file exactly at the limit (10MB = 10240 KB)
        $file = UploadedFile::fake()->image('large-image.jpg')->size(10240);

        $response = $this->actingAs($user)->post(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'url',
            'location',
        ]);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_s3_url_can_be_used_in_editor_textarea(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        // Set to production environment for S3
        $originalEnv = config('app.env');
        config(['app.env' => 'production']);
        Storage::fake('s3');

        $user = User::factory()->create();
        $user->roles()->attach(5); // Admin role
        $this->actingAs($user);

        // Upload image to S3
        $file = UploadedFile::fake()->image('s3-editor-image.jpg', 800, 600);
        $uploadResponse = $this->postJson(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $uploadResponse->assertStatus(200);
        $imageUrl = $uploadResponse->json('url');

        // Verify file is in S3
        $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
        Storage::disk('s3')->assertExists($filename);

        // Create news with S3 image URL in summary (textarea.editor)
        $summary = '<p>News with S3 image:</p><img src="' . $imageUrl . '" alt="S3 Image" />';
        $body = '<p>Body content with S3 image:</p><img src="' . $imageUrl . '" alt="S3 Image" />';
        
        $newsResponse = $this->post(route('add_news'), [
            'title' => 'News with S3 Image',
            'language' => 'en',
            'summary' => $summary,
            'body' => $body,
            'published' => 0,
        ]);

        $newsResponse->assertRedirect();

        // Verify the S3 URL was stored in the news
        $this->assertDatabaseHas('news', [
            'title' => 'News with S3 Image',
        ]);

        $news = News::where('title', 'News with S3 Image')->first();
        $this->assertNotNull($news);
        $this->assertStringContainsString($imageUrl, $news->summary);
        $this->assertStringContainsString($imageUrl, $news->body);

        // Restore environment
        config(['app.env' => $originalEnv]);
    }

    /**
     * @test
     */
    public function uploadt_image_from_editor_public_disk_url_can_be_used_in_editor_textarea(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        // Set to non-production environment for public disk
        $originalEnv = config('app.env');
        config(['app.env' => 'local']);
        Storage::fake('public');

        $user = User::factory()->create();
        $user->roles()->attach(5); // Admin role
        $this->actingAs($user);

        // Upload image to public disk
        $file = UploadedFile::fake()->image('public-editor-image.png', 600, 400);
        $uploadResponse = $this->postJson(route('upload.image.from.editor'), [
            'upload' => $file,
        ]);

        $uploadResponse->assertStatus(200);
        $imageUrl = $uploadResponse->json('url');

        // Verify file is in public disk
        $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
        Storage::disk('public')->assertExists($filename);

        // Create news with public disk image URL in summary and body (textarea.editor)
        $summary = '<p>News with public image:</p><img src="' . $imageUrl . '" alt="Public Image" />';
        $body = '<p>Body content with public image:</p><img src="' . $imageUrl . '" alt="Public Image" />';
        
        $newsResponse = $this->post(route('add_news'), [
            'title' => 'News with Public Image',
            'language' => 'en',
            'summary' => $summary,
            'body' => $body,
            'published' => 0,
        ]);

        $newsResponse->assertRedirect();

        // Verify the public URL was stored in the news
        $this->assertDatabaseHas('news', [
            'title' => 'News with Public Image',
        ]);

        $news = News::where('title', 'News with Public Image')->first();
        $this->assertNotNull($news);
        $this->assertStringContainsString($imageUrl, $news->summary);
        $this->assertStringContainsString($imageUrl, $news->body);

        // Restore environment
        config(['app.env' => $originalEnv]);
    }
}
