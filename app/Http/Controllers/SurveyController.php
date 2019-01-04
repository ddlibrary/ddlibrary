<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyQuestion;
use App\SurveyAnswer;
use App\SurveySettings;
use App\SurveyQuestionOption;

use Redirect;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::all();
        return view('admin.surveys.survey.list', compact('surveys'));
    }

    public function edit($id)
    {
        $survey = Survey::find($id);
        return view('admin.surveys.survey.edit',compact('survey'));
    }

    public function create()
    {   
        return view('admin.surveys.survey.create');
    }

    public function updateSurvey($id,Request $request)
    {
        $survey = Survey::find($id);
        $survey->name = $request['name'];
        $survey->state = $request['state'];
        $survey->save();
        return Redirect::back()->with('status', 'Survey Updated!');
    }

    public function postSurvey(Request $request)
    {
        $survey = new Survey();
        $survey->name = $request['name'];
        $survey->state = $request['state'];
        $survey->save();
        return Redirect::back()->with('status', 'Survey Created!');
    }

    public function delete($id)
    {
        $survey = Survey::find($id);
        $survey->delete();
        return Redirect::back()->with('status', 'Survey Deleted!');
    }

    public function storeSurvey(Request $request)
    {   
        foreach ($request->single_choice as $key => $value) {
            // key is question and value is selected option
            $surveyAnswer = new SurveyAnswer();
            $surveyAnswer->question_id = $key;
            $surveyAnswer->answer_id = $value;
            $surveyAnswer->ip = \Request::ip();
            $surveyAnswer->save();
        }

        foreach ($request->multi_choice as $key => $value) {
            // key is option and value is question
            $surveyAnswer = new SurveyAnswer();
            $surveyAnswer->question_id = $value;
            $surveyAnswer->answer_id = $key;
            $surveyAnswer->ip = \Request::ip();
            $surveyAnswer->save();
        }

        foreach ($request->descriptive as $key => $value) {
            // key is question and value the text inserted
            $surveyAnswer = new SurveyAnswer();
            $surveyAnswer->question_id = $key;
            $surveyAnswer->description = $value;
            $surveyAnswer->ip = \Request::ip();
            $surveyAnswer->save();
        }
        echo true;   
    }

    public function surveyQuestions($id)
    {
        $survey = Survey::find($id);
        $survey_questions = SurveyQuestion::where('survey_id', $survey->id)->get();
        return view('admin.surveys.question.list', compact('survey','survey_questions'));
    }
    
    public function createQuestion($id)
    {
        $survey = Survey::find($id);
        return view('admin.surveys.question.create', compact('survey'));
    }

    public function storeQuestion(Request $request)
    {
        $question = new SurveyQuestion();
        $question->text = $request['text'];
        $question->type = $request['type'];
        $question->survey_id = $request['survey_id'];
        $question->save();

        if ($question->type != "descriptive"){
            foreach ($request->options as $option_text) {
                $option = new SurveyQuestionOption();
                $option->text = $option_text;
                $option->question_id=$question->id;
                $option->save();
            }
        }
        return Redirect::back()->with('status', 'Question Added!');
    }

    public function deleteQuestion($id)
    {
        $question = SurveyQuestion::find($id);
        $question->delete();
        return Redirect::back()->with('status', 'Survey\'s Question Deleted!');
    }

    public function deleteOption($id)
    {
        $option = SurveyQuestionOption::find($id);
        $option->delete();
        return Redirect::back()->with('status', 'Question\'s Option Deleted!');
    }

    public function viewOptions($survey_id,$id)
    {
        $question = SurveyQuestion::find($id);
        $survey = $question->survey;
        $questin_options = $question->options;
        return view('admin.surveys.option.view', compact('question', 'questin_options','survey'));
    }

    public function createOption($survey_id, $question_id)
    {
        $survey = Survey::find($survey_id);
        $question = SurveyQuestion::find($question_id);
        return view('admin.surveys.option.create', compact('survey','question'));
    }

    public function storeOption(Request $request)
    {
        $option = new SurveyQuestionOption();
        $option->question_id = $request['question_id'];
        $option->text = $request['text'];
        $option-> save();
        return Redirect::back()->with('status', 'Question Option Created!');
    }

    public function getPopUpTime()
    {
        $survey_modal_time = SurveySettings::all()->first();
        return view('admin.surveys.setting.view', compact('survey_modal_time'));
    }

    public function createSurveyModalTime()
    {
        return view('admin.surveys.setting.create');
    }

    public function storeSurveyModalTime(Request $request)
    {
        $survey_modal_time = new SurveySettings();
        $survey_modal_time->time = $request['time'];
        $survey_modal_time->save();
        return Redirect::back()->with('status', 'Popup Time Created!');
    }

    public function editSurveyModalTime()
    {
        $survey_modal_time = SurveySettings::all()->first();
        return view('admin.surveys.setting.edit', compact('survey_modal_time'));
    }

    public function updateSurveyModalTime($id,Request $request)
    {
        $survey_modal_time = SurveySettings::find($id);
        $survey_modal_time->time = $request['time'];
        $survey_modal_time->save();
        return Redirect::back()->with('status', 'Popup Time Updated!');
    }
}
