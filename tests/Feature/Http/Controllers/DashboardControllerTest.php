<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\News;
use App\Models\Page;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DashboardController
 */
class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $users = User::factory(10)->create();
        $resources = Resource::factory(10)->create();
        $news = News::factory(10)->create();
        $pages = Page::factory(10)->create();

        $response = $this->actingAs($admin)->get('en/admin');

        $response->assertOk();
        $response->assertViewIs('admin.main');
        $response->assertViewHas('totalUsers', User::count());
        $response->assertViewHas('latestUsers', User::orderBy('id', 'desc')->take(5)->get());
        $response->assertViewHas('totalResources', Resource::count());
        $response->assertViewHas('latestResources', Resource::orderBy('id', 'desc')->take(5)->get());
        $response->assertViewHas('totalNews', News::count());
        $response->assertViewHas('latestNews', News::orderBy('id', 'desc')->take(5)->get());
        $response->assertViewHas('totalPages', Page::count());
        $response->assertViewHas('latestPages', Page::orderBy('id', 'desc')->take(5)->get());

        $response->assertSee('Dashboard');
        $response->assertSee(User::count());
        $response->assertSee(Resource::count());
        $response->assertSee(News::count());
        $response->assertSee(Page::count());
    }

    public function test_dashboard_page_is_accessible_to_authenticated_admin_users(): void
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->get('en/admin');

        $response->assertStatus(200);
    }

    public function test_dashboard_page_is_not_accessible_to_normal_authenticated_users(): void
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('en/admin');

        $response->assertStatus(302);
    }

    public function test_dashboard_page_is_not_accessible_to_guests(): void
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('en/admin');

        $response->assertRedirect('login');
    }

    public function test_dashboard_contains_user_specific_information(): void
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->get('en/admin');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_dashboard_loads_required_components(): void
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->get('en/admin');

        $response->assertSee('Latest Users');
    }
}
