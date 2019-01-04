<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyQuestion;
use App\SurveyAnswer;
use App\SurveySettings;
use App\SurveyQuestionOption;
use Redirect;

class SurveyAnswerController extends Controller{

	public function allQuestions()
    {
        $this->middleware('admin');
        $survey_questions = SurveyQuestion::all();
        return view('admin.surveys.result.view', compact('survey_questions'));        
    }

    public function questionAnswers($id)
    {
    	$this->middleware('admin');
    	$question = SurveyQuestion::find($id);

        if ($question->type == 'descriptive'){
            $descriptive_answers = SurveyAnswer::where(['question_id' => $question ->id])->get();
        }else{
            $survey_question_options = $question->options;
        }
    	return view('admin.surveys.result.result', compact('question','survey_question_options','descriptive_answers')); 
    }

    public function storeUserSurvey(Request $request)
    {   
    	if ($request->single_choice){
    		foreach ($request->single_choice as $key => $value) {
            // key is question and value is selected option
            $surveyAnswer = new SurveyAnswer();
            $surveyAnswer->question_id = $key;
            $surveyAnswer->answer_id = $value;
            $surveyAnswer->ip = \Request::ip();
            $surveyAnswer->save();
        	}
    	}
        
    	if ($request->multi_choice){
    		foreach ($request->multi_choice as $key => $value) {
            // key is option and value is question
            $surveyAnswer = new SurveyAnswer();
            $surveyAnswer->question_id = $value;
            $surveyAnswer->answer_id = $key;
            $surveyAnswer->ip = \Request::ip();
            $surveyAnswer->save();
        	}
    	}
        
    	if ($request->descriptive){
	        foreach ($request->descriptive as $key => $value) {
	            // key is question and value the text inserted
	            $surveyAnswer = new SurveyAnswer();
	            $surveyAnswer->question_id = $key;
	            $surveyAnswer->description = $value;
	            $surveyAnswer->ip = \Request::ip();
	            $surveyAnswer->save();
        	}
        }
        echo true;   
    }
}
