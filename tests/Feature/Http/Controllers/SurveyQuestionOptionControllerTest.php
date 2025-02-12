<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SurveyQuestionOptionController
 */
class SurveyQuestionOptionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function add_translate_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        
        $surveyQuestion = SurveyQuestion::factory()->create();
        $surveyQuestionOption = SurveyQuestionOption::factory()->create();
        $survey = Survey::factory()->create();


        $response = $this->actingAs($admin)->get("en/admin/survey/question/option/add/translate/$surveyQuestionOption->id/en");

        $response->assertOk();
        $response->assertViewIs('admin.surveys.option.add_translation');
        $response->assertViewHas('tnid');
        $response->assertViewHas('lang');
        $response->assertViewHas('question');
        $response->assertViewHas('survey');

    }

    /**
     * @test
     */
    public function create_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        
        $survey = Survey::factory()->create();
        $surveyQuestion = SurveyQuestion::factory()->create();
        $surveyQuestionOption = SurveyQuestionOption::factory()->create();


        $response = $this->actingAs($admin)->get("en/admin/survey/$survey->id/question/$surveyQuestionOption->id/option/create");

        $response->assertOk();
        $response->assertViewIs('admin.surveys.option.create');
        $response->assertViewHas('survey');
        $response->assertViewHas('question');

    }

    /**
     * @test
     */
    public function delete_returns_an_ok_response(): void
    {

        $this->refreshApplicationWithLocale('en');
        
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        
        $surveyQuestionOption = SurveyQuestionOption::factory()->create();


        $response = $this->actingAs($admin)->get("en/admin/survey/question/option/delete/$surveyQuestionOption->id");

        $response->assertRedirect();

        $this->assertEquals(0, SurveyQuestionOption::find($surveyQuestionOption->id));

    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        
        $survey = Survey::factory()->create();
        $surveyQuestion = SurveyQuestion::factory()->create();
        $surveyQuestionOption = SurveyQuestionOption::factory()->create();
        $surveyQuestions = SurveyQuestion::factory()->times(3)->create();
        $surveyQuestionOptions = SurveyQuestionOption::factory()->times(3)->create();


        $response = $this->actingAs($admin)->get("en/admin/survey/$survey->id/question/$surveyQuestionOption->id/view_options");

        $response->assertOk();
        $response->assertViewIs('admin.surveys.option.list');
        $response->assertViewHas('question_self');
        $response->assertViewHas('questin_options');
        $response->assertViewHas('survey');

    }

    /**
     * @test
     */
    public function store_returns_an_ok_response(): void
    {

        $this->refreshApplicationWithLocale('en');
        
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        
        $surveyQuestionOption = SurveyQuestionOption::factory()->create();
        $surveyQuestion = SurveyQuestion::factory()->create();


        $response = $this->actingAs($admin)->post(route('create_option'), [
                'question_id' => $surveyQuestion->id,
                'text' => 'new option',
                'language' => 'en',
        ]);

        $response->assertRedirect();

    }

    /**
     * @test
     */
    public function view_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        
        $survey = Survey::factory()->create();
        $surveyQuestionOption = SurveyQuestionOption::factory()->create();
        $surveyQuestion = SurveyQuestion::factory()->create(['tnid' => $survey->id]);


        $response = $this->actingAs($admin)->get("en/admin/survey/question/$surveyQuestion->id/option/{$surveyQuestionOption->id}/view/$survey->id");

        $response->assertOk();
        $response->assertViewIs('admin.surveys.option.view');
        $response->assertViewHas('options');
        $response->assertViewHas('option_self');
        $response->assertViewHas('question');
        $response->assertViewHas('survey');

    }

    // test cases...
}
