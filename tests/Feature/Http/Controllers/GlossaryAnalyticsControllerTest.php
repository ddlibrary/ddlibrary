<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\Device;
use App\Models\Browser;
use App\Models\Platform;
use App\Models\GlossarySubject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\GlossaryAnalyticsController
 */
class GlossaryAnalyticsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_glossary_analytics(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        Device::factory()->count(3)->create();
        Browser::factory()->count(3)->create();
        Platform::factory()->count(3)->create();
        GlossarySubject::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('en/admin/analytics/glossary');

        $response->assertOk();
        $response->assertViewIs('admin.analytics.glossary.index');
        $response->assertViewHasAll([
            'languages',
            'genders',
            'glossarySubjects',
            'devices',
            'platforms',
            'browsers',
            'totalViews',
            'totalRegisteredUsersViews',
            'totalGuestViews',
            'platformCounts',
            'browserCounts',
            'glossarySubjectCounts',
            'totalViewsBasedOnLanguage',
            'status',
        ]);
    }

    public function test_view_glossary_analytics_report(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        Device::factory()->count(3)->create();
        Browser::factory()->count(3)->create();
        Platform::factory()->count(3)->create();
        GlossarySubject::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('en/admin/analytics/reports/glossary');

        $response->assertOk();
        $response->assertViewIs('admin.analytics.glossary.get-views');
        $response->assertViewHasAll([
            'views',
            'glossarySubjects',
            'languages',
            'genders',
            'devices',
            'browsers',
            'platforms',
        ]);
    }

    public function test_non_admin_cannot_access_glossary_analytics(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        $nonAdminUser = User::factory()->create();
        $nonAdminUser->roles()->attach(6);

        $response = $this->get('en/admin/analytics/glossary');

        $response->assertStatus(302);

        $response = $this->get('en/admin/analytics/reports/glossary');

        $response->assertStatus(302);
    }
}
