<?php

namespace App\Http\Controllers;

use App\Survey;
use App\SurveyQuestion;
use Config;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Redirect;

class SurveyController extends Controller
{
    public function index(): View
    {
        $lang = Config::get('app.locale');
        $surveys = Survey::where('language', $lang)->get();

        return view('admin.surveys.survey.list', compact('surveys'));
    }

    public function view(Survey $surveys, $id, $tnid): View
    {
        $surveys = $surveys->where('tnid', $tnid)->get();
        $survey_self = $surveys->find($id);

        return view('admin.surveys.survey.view', compact('surveys', 'survey_self'));
    }

    public function create(): View
    {
        return view('admin.surveys.survey.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $survey = new Survey();
        $survey->name = $request['name'];
        $survey->state = $request['state'];
        $survey->language = $request['language'];
        $survey->save();

        // update the tnid
        $created_survey = Survey::find($survey->id);
        if ($request['tnid']) {
            $created_survey->tnid = $request['tnid'];
        } else {
            $created_survey->tnid = $survey->id;
        }
        $created_survey->save();

        return Redirect::back()->with('status', 'Survey Created!');
    }

    public function edit($id): View
    {
        $survey = Survey::find($id);

        return view('admin.surveys.survey.edit', compact('survey'));
    }

    public function update($id, Request $request): RedirectResponse
    {
        $survey = Survey::find($id);
        $survey->name = $request['name'];
        $survey->state = $request['state'];
        $survey->language = $request['language'];
        $survey->save();

        return Redirect::back()->with('status', 'Survey Updated!');
    }

    public function delete($id): RedirectResponse
    {
        $survey = Survey::find($id);
        $survey->delete();

        return Redirect::back()->with('status', 'Survey Deleted!');
    }

    public function addTranslate($tnid, $lang): View
    {
        return view('admin.surveys.survey.add_translation', compact('tnid', 'lang'));
    }

    public function report($id): View
    {
        $lang = Config::get('app.locale');
        $survey = Survey::find($id);
        $survey_questions = SurveyQuestion::where(['survey_id' => $survey->tnid, 'language' => $lang])->get();

        return view('admin.surveys.survey.report', compact('survey', 'survey_questions'));
    }
}
