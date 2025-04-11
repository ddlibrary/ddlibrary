<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SurveyAnswerController
 */
class SurveyAnswerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function all_questions_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5); // Attach the admin role

        // Create survey questions
        $surveyQuestions = SurveyQuestion::factory()->count(3)->create(['language' => config('app.locale')]);

        $response = $this->actingAs($admin)->get('en/admin/survey_questions');

        $response->assertOk();
        $response->assertViewIs('admin.surveys.result.view');
        $response->assertViewHas('survey_questions', $surveyQuestions);
    }

    /**
     * @test
     */
    public function question_answers_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5); // Attach the admin role

        // Create a survey question and its options
        $surveyQuestion = SurveyQuestion::factory()->create();
        $surveyQuestionOption = SurveyQuestionOption::factory()->create(['question_id' => $surveyQuestion->id]);

        $response = $this->actingAs($admin)->get('en/admin/survey_question/answers/' . $surveyQuestion->id);

        $response->assertOk();
        $response->assertViewIs('admin.surveys.result.result');
        $response->assertViewHas('question', $surveyQuestion);
        $response->assertViewHas('survey_question_options', $surveyQuestion->options);
    }

    /**
     * @test
     */
    public function store_user_survey_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5); // Attach the admin role

        // Create a survey question and its options
        $surveyQuestion = SurveyQuestion::factory()->create();
        $surveyQuestionOption = SurveyQuestionOption::factory()->create(['question_id' => $surveyQuestion->id]);

        // Prepare the request data
        $requestData = [
            'single_choice' => [
                $surveyQuestion->id => $surveyQuestionOption->id,
            ],
            'descriptive' => [
                $surveyQuestion->id => 'This is a descriptive answer.',
            ],
        ];

        $response = $this->actingAs($admin)->post(route('survey'), $requestData);

        $response->assertOk();

        // Check if the answer was saved
        $this->assertDatabaseHas('survey_answers', [
            'question_id' => $surveyQuestion->tnid,
            'answer_id' => $surveyQuestionOption->tnid,
            'description' => 'This is a descriptive answer.',
        ]);
    }
}