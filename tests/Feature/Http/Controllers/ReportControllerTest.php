<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceLevel;
use App\Models\ResourceSubjectArea;
use App\Models\TaxonomyTerm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ReportController
 */
class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function resource_language_report_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resources = Resource::factory()->times(3)->create();
        $taxonomyTerms = TaxonomyTerm::factory()->times(3)->create();
        $resourceLevels = ResourceLevel::factory()->times(3)->create();

        $response = $this->get('en/admin/reports/languages');

        $response->assertOk();
    }

    #[Test]
    public function resource_priorities_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $taxonomyTerms = TaxonomyTerm::factory()->times(3)->create();

        $response = $this->get('en/resources/priorities');

        $response->assertOk();
        $response->assertViewIs('reports.priorities');
        $response->assertViewHas('subjects_list');
    }

    #[Test]
    public function resource_priorities_exclusion_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $taxonomyTerms = TaxonomyTerm::factory()->times(3)->create();

        $response = $this->get('en/resources/priorities/exclusion');

        $response->assertOk();
        $response->assertViewIs('reports.exclusion');
        $response->assertViewHas('subjects_list');
    }

    #[Test]
    public function resource_report_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $response = $this->get('en/admin/reports/resources');

        $response->assertOk();
        $response->assertViewIs('admin.reports.resource_reports');
        $response->assertViewHas('supported_locales');
    }

    #[Test]
    public function resource_subject_report_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $taxonomyTerms = TaxonomyTerm::factory()->times(3)->create();
        $resourceSubjectAreas = ResourceSubjectArea::factory()->times(3)->create();
        $resourceLevels = ResourceLevel::factory()->times(3)->create();

        $response = $this->get('en/admin/reports/resources/subjects');

        $response->assertOk();
    }
}
