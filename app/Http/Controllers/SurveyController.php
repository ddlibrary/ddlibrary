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

    public function create()
    {   
        return view('admin.surveys.survey.create');
    }

    public function store(Request $request)
    {
        $survey = new Survey();
        $survey->name = $request['name'];
        $survey->state = $request['state'];
        $survey->save();
        return Redirect::back()->with('status', 'Survey Created!');
    }

    public function edit($id)
    {
        $survey = Survey::find($id);
        return view('admin.surveys.survey.edit',compact('survey'));
    }

    public function update($id,Request $request)
    {
        $survey = Survey::find($id);
        $survey->name = $request['name'];
        $survey->state = $request['state'];
        $survey->save();
        return Redirect::back()->with('status', 'Survey Updated!');
    }

    public function delete($id)
    {
        $survey = Survey::find($id);
        $survey->delete();
        return Redirect::back()->with('status', 'Survey Deleted!');
    }
}
