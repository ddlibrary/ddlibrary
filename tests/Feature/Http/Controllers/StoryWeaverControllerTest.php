<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\StoryWeaverController
 */
class StoryWeaverControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function story_weaver_auth_returns_an_ok_response(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        UserProfile::factory()->create(['user_id' => $admin->id]);

        Session::put('previous_url', 'http://example.com');
        Session::put('landing_page', 'storyweaver_default');

        Config::set('constants.storyweaver_url', 'https://storyweaver.example.com');
        Config::set('storyweaver.config.secret', 'test-secret');

        // Mock Guzzle to return a successful response
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'success',
                'redirect_url' => 'https://storyweaver.org/redirect',
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $this->app->bind(Client::class, fn () => new Client(['handler' => $handlerStack]));

        $response = $this->actingAs($admin)->get(route('storyweaver-auth'));

        $response->assertRedirect('https://storyweaver.org/redirect');
    }

    #[Test]
    public function story_weaver_auth_aborts_with_a_500(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $userProfile = UserProfile::factory()->create(['user_id' => $admin->id]);

        // Mock session data
        Session::put('previous_url', 'http://example.com');
        Session::put('landing_page', 'storyweaver_default');

        Config::set('constants.storyweaver_url', null); // Simulate misconfiguration
        Config::set('storyweaver.config.secret', ''); // Ensure secret is also empty

        $response = $this->actingAs($admin)->get(route('storyweaver-auth'));

        $response->assertStatus(500);
    }

    #[Test]
    public function story_weaver_auth_aborts_with_a_405(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $userProfile = UserProfile::factory()->create(['user_id' => $admin->id]);

        // Mock session data
        Session::put('previous_url', 'http://example.com');
        Session::put('landing_page', 'storyweaver_default');

        // Simulate a method not allowed scenario
        $response = $this->actingAs($admin)->post(route('storyweaver-auth')); // Use POST instead of GET

        $response->assertStatus(405);
    }

    #[Test]
    public function story_weaver_auth_aborts_with_a_422(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $userProfile = UserProfile::factory()->create(['user_id' => $admin->id]);

        Session::put('previous_url', 'http://example.com');
        Session::put('landing_page', 'storyweaver_default');

        Config::set('constants.storyweaver_url', 'https://storyweaver.example.com');
        Config::set('storyweaver.config.secret', 'test-secret');

        // Mock Guzzle to return a 422 response
        $mock = new MockHandler([
            new Response(422),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $this->app->bind(Client::class, fn () => new Client(['handler' => $handlerStack]));

        $response = $this->actingAs($admin)->get(route('storyweaver-auth'));

        $response->assertStatus(422);
    }

    #[Test]
    public function story_weaver_confirmation_returns_an_ok_response(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $userProfile = UserProfile::factory()->create([
            'user_id' => $admin->id,
            'visited_storyweaver_disclaimer' => false,
        ]);

        Session::put('previous_url', 'http://example.com');
        Session::put('landing_page', 'storyweaver_default');

        $response = $this->actingAs($admin)->get(route('storyweaver-confirm', ['landing_page' => 'storyweaver_default']));

        $response->assertStatus(200);
        $response->assertViewIs('storyweaver.confirmation');
    }
}
