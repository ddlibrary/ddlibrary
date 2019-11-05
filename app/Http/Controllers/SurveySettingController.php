<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyQuestion;
use App\SurveyAnswer;
use App\SurveySettings;
use App\SurveyQuestionOption;
use Redirect;

class SurveySettingController extends Controller
{
    public function getSurveyModalTime()
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
