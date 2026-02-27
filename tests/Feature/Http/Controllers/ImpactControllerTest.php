<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ImpactController
 */
class ImpactControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get(url('en/impact'));

        $response->assertOk();
        $response->assertViewIs('impact.impact_page');
        $response->assertViewHas('totalResources');
        $response->assertViewHas('monthlyViews');
        $response->assertViewHas('totalSubjects');

        $this->assertNotNull($response->viewData('totalResources'));
        $this->assertNotNull($response->viewData('totalSubjects'));

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $responseUpdate = $this->actingAs($admin)->get('en/impact?update=true');
        $responseUpdate->assertOk();
        $responseUpdate->assertViewIs('impact.impact_page');
        $responseUpdate->assertViewHas('totalResources');
        $responseUpdate->assertViewHas('monthlyViews');
        $responseUpdate->assertViewHas('totalSubjects');
    }
}
