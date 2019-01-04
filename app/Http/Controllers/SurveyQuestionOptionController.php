<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyQuestion;
use App\SurveyAnswer;
use App\SurveySettings;
use App\SurveyQuestionOption;
use Redirect;

class SurveyQuestionOptionController extends Controller
{
	public function index($survey_id,$id)
    {
        $question = SurveyQuestion::find($id);
        $survey = $question->survey;
        $questin_options = $question->options;
        return view('admin.surveys.option.view', compact('question', 'questin_options','survey'));
    }

    public function create($survey_id, $question_id)
    {
        $survey = Survey::find($survey_id);
        $question = SurveyQuestion::find($question_id);
        return view('admin.surveys.option.create', compact('survey','question'));
    }

    public function store(Request $request)
    {
        $option = new SurveyQuestionOption();
        $option->question_id = $request['question_id'];
        $option->text = $request['text'];
        $option-> save();
        return Redirect::back()->with('status', 'Question Option Created!');
    }

    public function delete($id)
    {
        $option = SurveyQuestionOption::find($id);
        $option->delete();
        return Redirect::back()->with('status', 'Question\'s Option Deleted!');
    }
}
