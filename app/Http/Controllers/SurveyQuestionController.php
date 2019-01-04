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
        return view('admin.surveys.survey_questions_result', compact('survey_questions'));        
    }

    public function viewAnswers($id)
    {
    	$this->middleware('admin');
    	$question = SurveyQuestion::find($id);

        if ($question->type == 'descriptive'){
            $descriptive_answers = SurveyAnswer::where(['question_id' => $question ->id])->get();
        }else{
            $survey_question_options = $question->options;
        }
    	return view('admin.surveys.question_answers', compact('question','survey_question_options','descriptive_answers')); 
    }

}
