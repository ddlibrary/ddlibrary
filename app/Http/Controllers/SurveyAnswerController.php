<?php

namespace App\Http\Controllers;

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use Config;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurveyAnswerController extends Controller
{
    public function allQuestions(): View
    {
        $this->middleware('admin');
        $lang = config('app.locale');
        $survey_questions = SurveyQuestion::where('language', $lang)->get();

        return view('admin.surveys.result.view', compact('survey_questions'));
    }

    public function questionAnswers($id): View
    {
        $this->middleware('admin');
        $question = SurveyQuestion::find($id);

        $descriptive_answers = null;
        $survey_question_options = null;

        if ($question->type == 'descriptive') {
            $descriptive_answers = SurveyAnswer::where(['answer_id' => $question->tnid])->get();
        } else {
            $survey_question_options = $question->options;
        }

        return view('admin.surveys.result.result', compact('question', 'survey_question_options', 'descriptive_answers'));
    }

    public function storeUserSurvey(Request $request)
    {
        if ($request->single_choice) {
            foreach ($request->single_choice as $key => $value) {
                // key is question and value is selected option
                $question = SurveyQuestion::find($key);
                $answer = SurveyQuestionOption::find($value);

                $surveyAnswer = new SurveyAnswer();
                $surveyAnswer->question_id = $question->tnid;
                $surveyAnswer->answer_id = $answer->tnid;
                $surveyAnswer->ip = \Request::ip();
                $surveyAnswer->language = \LaravelLocalization::getCurrentLocale();
                $surveyAnswer->save();
            }
        }

        if ($request->multi_choice) {
            foreach ($request->multi_choice as $key => $value) {
                // key is option and value is question
                $question = SurveyQuestion::find($value);
                $answer = SurveyQuestionOption::find($key);

                $surveyAnswer = new SurveyAnswer();
                $surveyAnswer->question_id = $question->tnid;
                $surveyAnswer->answer_id = $answer->tnid;
                $surveyAnswer->ip = \Request::ip();
                $surveyAnswer->language = \LaravelLocalization::getCurrentLocale();
                $surveyAnswer->save();
            }
        }

        if ($request->descriptive) {
            foreach ($request->descriptive as $key => $value) {
                // key is question and value the text inserted
                $question = SurveyQuestion::find($key);

                $surveyAnswer = new SurveyAnswer();
                $surveyAnswer->question_id = $key;
                $surveyAnswer->answer_id = $question->tnid;
                $surveyAnswer->description = $value;
                $surveyAnswer->ip = \Request::ip();
                $surveyAnswer->language = \LaravelLocalization::getCurrentLocale();
                $surveyAnswer->save();
            }
        }
        echo true;
    }
}
