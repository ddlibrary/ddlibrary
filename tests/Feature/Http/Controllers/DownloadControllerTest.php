<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DownloadCount;
use App\Models\User;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DownloadController
 */
class DownloadControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get(url('en/admin/analytics/reports/downloads'));

        $response->assertOk();
        $response->assertViewIs('admin.downloads.download_list');
        $response->assertViewHas('records');
        $response->assertViewHas('filters');
        $response->assertViewHas('genders');
        $response->assertViewHas('languages');
    }

    public function test_unauthorized_user_cannot_access_downloads()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get(url('en/admin/analytics/reports/downloads'));

        $response->assertRedirect(url('login'));
    }

    public function test_non_admin_user_cannot_access_downloads()
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(url('en/admin/analytics/reports/downloads'));

        $response->assertStatus(302);
    }
}
