<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyQuestion;
use App\SurveyAnswer;
use App\SurveySettings;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::find(1);
        $surveyQuestions = SurveyQuestion::where('survey_id', 1)->first();
        return view('survey.survey_view', compact('surveys', 'surveyQuestions'));
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

        $survey_modal_time = SurveySettings::all()->first();
        return view('admin.surveys.survey_settings', compact('survey_modal_time'));
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
        return view('admin.surveys.survey_settings', compact('survey_modal_time'));
    }
}
