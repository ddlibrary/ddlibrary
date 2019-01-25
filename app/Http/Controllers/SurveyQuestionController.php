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

    public function view(SurveyQuestion $questions,$surveyid, $id, $tnid)
    {
        $survey = Survey::find($surveyid);
        $questions = $questions->where('tnid', $tnid)->get();
        $question_self = $questions->find($id);
        return view('admin.surveys.question.view', compact('questions', 'question_self', 'survey'));   
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
        $question->language = $request['language'];
        $question->survey_id = $request['survey_id'];
        $question->save();

        if ($question->type != "descriptive"){
            foreach ($request->options as $option_text) {
                if ($option_text){
                    $option = new SurveyQuestionOption();
                    $option->text = $option_text;
                    $option->question_id=$question->id;
                    $option->save();
                }
            } 
        }

        // update the tnid
        $created_question = SurveyQuestion::find($question->id);
        if ($request['tnid']){
            $created_question->tnid = $request['tnid'];
        }else{
            $created_question->tnid = $question->id;
        }
        $created_question->save();
        return Redirect::back()->with('status', 'Question Added!');
    }

    public function delete($id)
    {
        $question = SurveyQuestion::find($id);
        $question->delete();
        return Redirect::back()->with('status', 'Survey\'s Question Deleted!');
    }

    public function addTranslate($tnid, $lang)
    {
        $question = SurveyQuestion::where('id', $tnid)->first();
        $survey = Survey::find($question->survey_id);
        return view('admin.surveys.question.add_translation', compact('tnid', 'lang','survey','question'));   
    }
}
