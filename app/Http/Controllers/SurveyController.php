<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyQuestion;
use App\SurveyAnswer;
use App\SurveySettings;
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
        return redirect()->back();
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
        return Redirect::back()->with('status', 'Pop Up Time Created!');
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
        return Redirect::back()->with('status', 'Pop Up Time Updated!');
    }
}
