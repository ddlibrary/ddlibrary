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
    	$count = SurveyAnswer::where(['question_id'=> $question_id, 'answer_id' => $answer_id])->count();
    	return $count;
    }
}
