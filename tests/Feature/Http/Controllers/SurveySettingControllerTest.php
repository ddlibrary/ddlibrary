<?php

namespace Tests\Feature\Http\Controllers;

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
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get('admin/create_survey_modal_time');

        $response->assertOk();
        $response->assertViewIs('admin.surveys.setting.create');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_survey_modal_time_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $surveySetting = \App\Models\SurveySetting::factory()->create();
        $surveySettings = \App\Models\SurveySetting::factory()->times(3)->create();

        $response = $this->get('admin/edit_survey_modal_time');

        $response->assertOk();
        $response->assertViewIs('admin.surveys.setting.edit');
        $response->assertViewHas('survey_modal_time');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function get_survey_modal_time_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $surveySetting = \App\Models\SurveySetting::factory()->create();
        $surveySettings = \App\Models\SurveySetting::factory()->times(3)->create();

        $response = $this->get('admin/survey_time');

        $response->assertOk();
        $response->assertViewIs('admin.surveys.setting.view');
        $response->assertViewHas('survey_modal_time');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_survey_modal_time_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->post(route('store_survey_modal_time'), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_survey_modal_time_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $surveySetting = \App\Models\SurveySetting::factory()->create();

        $response = $this->post(route('update_survey_modal_time', ['id' => $surveySetting->id]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
