<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function getAnswersByOption($question_id)
    {
        $records = DB::table('survey_question_options AS sqo')
            ->select(
                'sqo.text', 
                'sa.language',
                DB::Raw('count(sa.answer_id) as total')
            )
            ->join('survey_answers AS sa','sa.answer_id','=','sqo.id')
            ->where('sqo.question_id', $question_id)
            ->groupBy(
                'sa.language',
                'sqo.text'
            )
            ->get();
        return $records;
    }
}
