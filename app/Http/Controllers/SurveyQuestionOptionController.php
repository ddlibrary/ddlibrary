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
        return view('admin.surveys.option.list', compact('question', 'questin_options','survey'));
    }

    public function view(SurveyQuestionOption $options,$questionid, $id, $tnid)
    {
        $question = SurveyQuestion::find($questionid);
        $survey = Survey::find($question->survey_id);
        $options = $options->where('tnid', $tnid)->get();
        $option_self = $options->find($id);
        return view('admin.surveys.option.view', compact('options', 'option_self', 'question','survey'));   
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
        $option->language = $request['language'];
        $option-> save();

        // update the tnid
        $created_option = SurveyQuestionOption::find($option->id);
        if ($request['tnid']){
            $created_option->tnid = $request['tnid'];
        }else{
            $created_option->tnid = $option->id;
        }
        $created_option->save();
        return Redirect::back()->with('status', 'Question Option Created!');
    }

    public function delete($id)
    {
        $option = SurveyQuestionOption::find($id);
        $option->delete();
        return Redirect::back()->with('status', 'Question\'s Option Deleted!');
    }

    public function addTranslate($tnid, $lang)
    {
        $option = SurveyQuestionOption::where('tnid', $tnid)->get();
        $question = SurveyQuestion::find($option[0]->question_id);  
        $survey = Survey::find($question->survey_id);
        return view('admin.surveys.option.add_translation', compact('tnid', 'lang','question','survey'));   
    }
}
