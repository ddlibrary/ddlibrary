<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyQuestion;
use App\SurveyAnswer;
use App\SurveySettings;
use App\SurveyQuestionOption;
use Redirect;
use Config;

class SurveyAnswerController extends Controller{

	public function allQuestions()
    {
        $this->middleware('admin');
        $lang = Config::get('app.locale'); 
        $survey_questions = SurveyQuestion::where('language', $lang)->get();
        return view('admin.surveys.result.view', compact('survey_questions'));        
    }

    public function questionAnswers($id)
    {
    	$this->middleware('admin');
    	$question = SurveyQuestion::find($id);
        $descriptive_answers = Null;
        $survey_question_options = Null;

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
                $question = SurveyQuestion::find($key);
                $answer = SurveyQuestionOption::find($value);

                $surveyAnswer = new SurveyAnswer();
                $surveyAnswer->question_id = $question->tnid;
                $surveyAnswer->answer_id = $answer->tnid;
                $surveyAnswer->ip = \Request::ip();
                $surveyAnswer->save();
        	}
    	}
        
    	if ($request->multi_choice){
    		foreach ($request->multi_choice as $key => $value) {
                // key is option and value is question
                $question = SurveyQuestion::find($value);
                $answer = SurveyQuestionOption::find($key);

                $surveyAnswer = new SurveyAnswer();
                $surveyAnswer->question_id = $question->tnid;
                $surveyAnswer->answer_id = $answer->tnid;
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
