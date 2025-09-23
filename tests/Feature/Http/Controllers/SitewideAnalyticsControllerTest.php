<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Browser;
use App\Models\Device;
use App\Models\Platform;
use App\Models\SitewidePageView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SitewideAnalyticsController
 */
class SitewideAnalyticsControllerTest extends TestCase
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

        SitewidePageView::factory()->times(10)->create();

        $response = $this->get('en/admin/analytics/sitewide');

        $response->assertOk();
        $response->assertViewIs('admin.analytics.sitewide.index');
        $response->assertViewHas('languages');
        $response->assertViewHas('genders');
        $response->assertViewHas('top10ViewedPages');
        $response->assertViewHas('totalViews');
        $response->assertViewHas('totalRegisteredUsersViews');
        $response->assertViewHas('totalGuestViews');
        $response->assertViewHas('platformCounts');
        $response->assertViewHas('browserCounts');
        $response->assertViewHas('totalViewsBasedOnLanguage');

    }

    /**
     * @test
     */
    public function view_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);
        
        $devices = Device::factory()->times(3)->create();
        $browsers = Browser::factory()->times(3)->create();
        $platforms = Platform::factory()->times(3)->create();
        SitewidePageView::factory()->times(10)->create();

        $response = $this->get('en/admin/analytics/reports/sitewide');

        $response->assertOk();
        $response->assertViewIs('admin.analytics.sitewide.get-views');
        $response->assertViewHas(['views', 'languages', 'genders', 'devices', 'browsers', 'platforms']);

    }

    /**
     * @test
     */
    public function view_returns_an_ok_response_with_pagination(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        // Create test data
        $devices = Device::factory()->times(3)->create();
        $browsers = Browser::factory()->times(3)->create();
        $platforms = Platform::factory()->times(3)->create();
        SitewidePageView::factory()->times(30)->create();

        $response = $this->get('en/admin/analytics/reports/sitewide?page=1');

        $response->assertOk();
        $response->assertViewIs('admin.analytics.sitewide.get-views');
        $response->assertViewHas(['views', 'languages', 'genders', 'devices', 'browsers', 'platforms']);

        // Check if pagination is working
        $this->assertCount(15, $response->viewData('views'));
    }

}
