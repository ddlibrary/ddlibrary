<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SurveyQuestionController
 */
class SurveyQuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function add_translate_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5); // Attach the admin role

        $surveyQuestion = SurveyQuestion::factory()->create();
        $survey = Survey::factory()->create();

        $response = $this->actingAs($admin)->get("en/admin/survey/question/add/translate/$surveyQuestion->id/en");

        $response->assertOk();
        $response->assertViewIs('admin.surveys.question.add_translation');
        $response->assertViewHas('tnid');
        $response->assertViewHas('lang');
        $response->assertViewHas('survey');
        $response->assertViewHas('question');
    }

    /**
     * @test
     */
    public function create_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $surveyQuestion = SurveyQuestion::factory()->create();
        $survey = Survey::factory()->create();

        $response = $this->actingAs($admin)->get("en/admin/survey/question/add/$survey->id");

        $response->assertOk();
        $response->assertViewIs('admin.surveys.question.create');
        $response->assertViewHas('survey', $survey);
    }

    /**
     * @test
     */
    public function delete_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $surveyQuestion = SurveyQuestion::factory()->create();

        $response = $this->actingAs($admin)->get("en/admin/survey/question/delete/$surveyQuestion->id");

        $response->assertRedirect();
        $this->assertEquals(0, SurveyQuestion::find($surveyQuestion->id));
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $surveyQuestion = SurveyQuestion::factory()->create();
        $surveyQuestions = SurveyQuestion::factory()->times(3)->create();

        $response = $this->actingAs($admin)->get("en/admin/survey/questions/$surveyQuestion->id");

        $response->assertOk();
        $response->assertViewIs('admin.surveys.question.list');
        $response->assertViewHas('survey');
        $response->assertViewHas('survey_questions');
        $this->assertEquals(4, SurveyQuestion::count());
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $survey = Survey::factory()->create();

        $response = $this->actingAs($admin)->post(route('create_question'), [
            'text' => 'Sample Question Text',
            'type' => 'single_choice',
            'language' => 'en',
            'survey_id' => $survey->id,
            'options' => ['Option 1', 'Option 2'],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('survey_questions', [
            'text' => 'Sample Question Text',
            'type' => 'single_choice',
            'language' => 'en',
            'survey_id' => $survey->id,
        ]);
        $this->assertDatabaseHas('survey_question_options', [
            'text' => 'Option 1',
            'language' => 'en',
        ]);
        $this->assertDatabaseHas('survey_question_options', [
            'text' => 'Option 2',
            'language' => 'en',
        ]);
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
        $surveyQuestion = SurveyQuestion::factory()->create(['tnid' => $survey->id]);

        $response = $this->actingAs($admin)->get("en/admin/survey/$survey->id/question/view/$surveyQuestion->id/$survey->id");

        $response->assertOk();
        $response->assertViewIs('admin.surveys.question.view');
        $response->assertViewHas('questions');
        $response->assertViewHas('question_self');
        $response->assertViewHas('survey');
    }
}
