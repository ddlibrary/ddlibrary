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
        return view('admin.surveys.list', compact('surveys'));
    }

    public function edit($id)
    {
        $survey = Survey::find($id);
        return view('admin.surveys.edit',compact('survey'));
    }

    public function updateSurvey($id,Request $request)
    {
        $survey = Survey::find($id);
        $survey->name = $request['name'];
        $survey->save();
        return Redirect::back()->with('status', 'Survey Updated!');
    }

    public function create()
    {   
        return view('admin.surveys.create');
    }

    public function postSurvey(Request $request)
    {
        $survey = new Survey();
        $survey->name = $request['name'];
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
        parse_str(request('mydata'), $output);
        $answer = $output['useful'];  // value

        $surveyAnswer = new SurveyAnswer();
        $surveyAnswer->question_id = 1;
        $surveyAnswer->answer = $answer;
        $surveyAnswer->ip = \Request::ip();
        $surveyAnswer->save();
        echo true;   
    }

    public function surveyQuestions($id)
    {
        $survey = Survey::find($id);
        $survey_questions = SurveyQuestion::where('survey_id', $survey->id)->get();
        return view('admin.surveys.view_questions', compact('survey','survey_questions'));
    }
    
    public function createQuestion($id)
    {
        $survey = Survey::find($id);
        return view('admin.surveys.create_question', compact('survey'));
    }

    public function storeQuestion(Request $request)
    {

        dd($request);
        // $survey_question = new SurveyQuestion();
        // $survey_question->text = $request['question'];
        // $survey_question->survey_id = $request['survey_id'];
        // $survey_question->save();
        // return Redirect::back()->with('status', 'Question Added!');
    }

    public function editQuestion($survey_id, $id)
    {
        $question = SurveyQuestion::find($id);
        $survey = Survey::find($survey_id);
        return view('admin.surveys.edit_question',compact('question','survey'));
    }

    public function updateQuestion($id,Request $request)
    {
        $question = SurveyQuestion::find($id);
        $question->text = $request['text'];
        $question->save();
        return Redirect::back()->with('status', 'Question Updated!');
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
        $survey = Survey::find($survey_id);
        $questin_options = SurveyQuestionOption::where('question_id', $question->id)->get();
        return view('admin.surveys.view_options', compact('question', 'questin_options','survey'));
    }

    public function createOption($survey_id, $question_id)
    {
        $survey = Survey::find($survey_id);
        $question = SurveyQuestion::find($question_id);
        return view('admin.surveys.create_option', compact('survey','question'));
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
        return view('admin.surveys.survey_settings', compact('survey_modal_time'));
    }

    public function createSurveyModalTime()
    {
        return view('admin.surveys.create_survey_settings');
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
        return view('admin.surveys.edit_survey_settings', compact('survey_modal_time'));
    }

    public function updateSurveyModalTime($id,Request $request)
    {
        $survey_modal_time = SurveySettings::find($id);
        $survey_modal_time->time = $request['time'];
        $survey_modal_time->save();
        return Redirect::back()->with('status', 'Popup Time Updated!');
    }
}
