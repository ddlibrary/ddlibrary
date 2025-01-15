<?php

namespace Tests\Feature\Resource;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User; // Adjust if needed based on your User model's namespace

class ExtractResourceImageUrlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_extracts_resource_image_urls_and_stores_them()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $baseUrl = 'https://library.darakhtdanesh.org';
        $defaultImage = $baseUrl . '/storage/files/placeholder_image.png';

        DB::table('resources')->insert([
            'id' => 1,
            'title' => 'Test Resource 1',
            'abstract' => '<p><img src="https://example.com/image1.png" /></p>',
            'language' => 'en',
            'user_id' => $user->id,
            'status' => 1,
            'tnid' => null,
            'image' => null,
        ]);

        DB::table('resources')->insert([
            'id' => 2,
            'title' => 'Test Resource 2',
            'abstract' => '<p>No image here</p>',
            'language' => 'en',
            'user_id' => $user->id,
            'status' => 0,
            'tnid' => null,
            'image' => null,
        ]);

        DB::table('resources')->insert([
            'id' => 3,
            'title' => 'Test Resource 3',
            'abstract' => '<p><img src="https://example.com/image2.png" /></p>',
            'language' => 'en',
            'user_id' => $user->id,
            'status' => 1,
            'tnid' => null,
            'image' => null,
        ]);

        // Additional record with a different image structure
        DB::table('resources')->insert([
            'id' => 4,
            'title' => 'Test Resource 4',
            'abstract' => '<p><img alt="" src="https://example.com/image3.png" /></p>',
            'language' => 'en',
            'user_id' => $user->id,
            'status' => 1,
            'tnid' => null,
            'image' => null,
        ]);

        // Run the command
        $this->artisan('app:extract:image-url')->assertExitCode(0);

        // Check the database for the expected results
        $this->assertEquals($baseUrl . '/storage/files/image1.png', DB::table('resources')->where('id', 1)->value('image'));
        $this->assertEquals($defaultImage, DB::table('resources')->where('id', 2)->value('image'));
        $this->assertEquals($baseUrl . '/storage/files/image2.png', DB::table('resources')->where('id', 3)->value('image'));
        $this->assertEquals($baseUrl . '/storage/files/image3.png', DB::table('resources')->where('id', 4)->value('image'));
    }
}
