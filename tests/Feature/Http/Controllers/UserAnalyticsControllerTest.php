<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserAnalyticsController
 */
class UserAnalyticsControllerTest extends TestCase
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
        $this->actingAs($admin);

        $response = $this->get('en/admin/analytics/user');
        $response->assertOk();
        $response->assertViewIs('admin.analytics.users.index');
        $response->assertViewHas(['roles', 'totalUsersBaseOnGenders', 'top10ActiveUsers', 'totalRegisteredUsers', 'totalUsers', 'totalGoogleUsers', 'totalFacebookUsers']);

    }
}
