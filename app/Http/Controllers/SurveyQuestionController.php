<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyQuestion;
use App\SurveyAnswer;
use App\SurveySettings;
use App\SurveyQuestionOption;
use Redirect;

class SurveyQuestionController extends Controller
{
    public function index($id)
    {
        $survey = Survey::find($id);
        $survey_questions = SurveyQuestion::where('survey_id', $survey->id)->get();
        return view('admin.surveys.question.list', compact('survey','survey_questions'));
    }
    
    public function create($id)
    {
        $survey = Survey::find($id);
        return view('admin.surveys.question.create', compact('survey'));
    }

    public function store(Request $request)
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

    public function delete($id)
    {
        $question = SurveyQuestion::find($id);
        $question->delete();
        return Redirect::back()->with('status', 'Survey\'s Question Deleted!');
    }
}
