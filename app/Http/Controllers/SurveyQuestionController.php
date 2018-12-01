<?php

namespace App\Http\Controllers;

use App\SurveyAnswer;
use App\SurveyQuestion;
use App\SurveyQuestionOption;
use Illuminate\Http\Request;

class SurveyQuestionController extends Controller
{

	public function index()
    {
        $this->middleware('admin');
        $survey_questions = SurveyQuestion::all();
        return view('admin.surveys.survey_questions', compact('survey_questions'));        
    }

    public function viewAnswers($id)
    {
    	$this->middleware('admin');
    	$question = SurveyQuestion::find($id);
    	$survey_question_options = SurveyQuestionOption::where('question_id', $id)->get();
    	return view('admin.surveys.question_answers', compact('question','survey_question_options')); 
    }

}
