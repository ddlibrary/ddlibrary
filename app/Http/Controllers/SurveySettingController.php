<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\SurveySetting;
use Illuminate\Http\Request;
use Redirect;

class SurveySettingController extends Controller
{
    public function getSurveyModalTime(): View
    {
        $survey_modal_time = SurveySetting::all()->first();

        return view('admin.surveys.setting.view', compact('survey_modal_time'));
    }

    public function createSurveyModalTime(): View
    {
        return view('admin.surveys.setting.create');
    }

    public function storeSurveyModalTime(Request $request): RedirectResponse
    {
        $survey_modal_time = new SurveySetting();
        $survey_modal_time->time = $request['time'];
        $survey_modal_time->save();

        return Redirect::back()->with('status', 'Popup Time Created!');
    }

    public function editSurveyModalTime(): View
    {
        $survey_modal_time = SurveySetting::all()->first();

        return view('admin.surveys.setting.edit', compact('survey_modal_time'));
    }

    public function updateSurveyModalTime($id, Request $request): RedirectResponse
    {
        $survey_modal_time = SurveySetting::find($id);
        $survey_modal_time->time = $request['time'];
        $survey_modal_time->save();

        return Redirect::back()->with('status', 'Popup Time Updated!');
    }
}
