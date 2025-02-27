<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ResourceAnalyticsController
 */
class ResourceAnalyticsControllerTest extends TestCase
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
        $taxonomyTerms = \App\Models\TaxonomyTerm::factory()->times(3)->create();

        $response = $this->get('en/admin/analytics/resource');

        $response->assertOk();
        $response->assertViewIs('admin.analytics.resource-analytics.index');
        $response->assertViewHas(['genders', 'languages', 'totalResources', 'sumOfAllIndividualDownloadedFileSizes', 'top10Authors', 'top10Publishers', 'top10DownloadedResources', 'top10DownloadedResourcesByFileSizes', 'top10FavoriteResources', 'subjectAreas', 'resourceTypes']);
    }
}
