<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\SurveySetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SurveySettingController
 */
class SurveySettingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_survey_modal_time_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get('en/admin/create_survey_modal_time');

        $response->assertOk();
        $response->assertViewIs('admin.surveys.setting.create');
    }

    /**
     * @test
     */
    public function edit_survey_modal_time_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $surveySetting = SurveySetting::factory()->create();
        $surveySettings = SurveySetting::factory()->times(3)->create();

        $response = $this->actingAs($admin)->get('en/admin/edit_survey_modal_time');

        $response->assertOk();
        $response->assertViewIs('admin.surveys.setting.edit');
        $response->assertViewHas('survey_modal_time');
    }

    /**
     * @test
     */
    public function get_survey_modal_time_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $surveySetting = SurveySetting::factory()->create();
        $surveySettings = SurveySetting::factory()->times(3)->create();

        $response = $this->actingAs($admin)->get('en/admin/survey_time');

        $response->assertOk();
        $response->assertViewIs('admin.surveys.setting.view');
        $response->assertViewHas('survey_modal_time');
    }

    /**
     * @test
     */
    public function store_survey_modal_time_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('store_survey_modal_time'), [
            'time' => 40000,
        ]);

        $response->assertRedirect();
        $this->assertEquals(40000, SurveySetting::value('time'));
    }

    /**
     * @test
     */
    public function update_survey_modal_time_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $surveySetting = SurveySetting::factory()->create();
        $response = $this->actingAs($admin)->post(route('update_survey_modal_time', ['id' => $surveySetting->id]), [
            'time' => 9001,
        ]);

        $response->assertRedirect();
        $this->assertEquals(9001, SurveySetting::value('time'));
    }
}
