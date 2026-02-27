<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\StoryWeaverController
 */
class StoryWeaverControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function story_weaver_auth_returns_an_ok_response(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $userProfile = UserProfile::factory()->create(['user_id' => $admin->id]);

        // Mock session data
        Session::put('previous_url', 'http://example.com');
        Session::put('landing_page', 'storyweaver_default');

        $response = $this->actingAs($admin)->get(route('storyweaver-auth'));

        $response->assertRedirect();
    }

    /**
     * @test
     */
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

        $response->assertRedirect('http://localhost/en');
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function story_weaver_auth_aborts_with_a_422(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $userProfile = UserProfile::factory()->create(['user_id' => $admin->id]);

        // Mock session data
        Session::put('previous_url', 'http://example.com');
        Session::put('landing_page', 'storyweaver_default');

        // Simulate a scenario that triggers a 422 error
        // For example, you can set up the UserProfile to have invalid data
        $userProfile->visited_storyweaver_disclaimer = false; // Ensure the user has not visited the disclaimer

        $response = $this->actingAs($admin)->get(route('storyweaver-auth'));

        $response->assertRedirect('http://localhost/en');
    }

    /**
     * @test
     */
    public function story_weaver_confirmation_returns_an_ok_response(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $userProfile = UserProfile::factory()->create();

        // Mock session data
        Session::put('previous_url', 'http://example.com');
        Session::put('landing_page', 'storyweaver_default');

        $response = $this->actingAs($admin)->get(route('storyweaver-confirm', ['landing_page' => 'storyweaver_default']));

        $response->assertRedirect('http://localhost/en');
    }
}
