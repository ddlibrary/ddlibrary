<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    /**
     * Get the count for answers.
    */
    public static function getQuestionAnswersCount($question_id, $answer_id)
    {
    	$question = SurveyQuestion::find($question_id);
    	$answer = SurveyQuestionOption::find($answer_id);
    	$count = SurveyAnswer::where(['question_id'=> $question->tnid, 'answer_id' => $answer->tnid])->count();
    	return $count;
    }
}
