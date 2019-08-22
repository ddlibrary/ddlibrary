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
    /**
     * Get the single/multiple option question answers.
    */
    public static function getAnswersByOption($question_id)
    {
        $records = DB::table('survey_question_options AS sqo')
        ->select(
            'sqo.text', 
            'sa.language',
            'sqo.id',
            DB::Raw('count(sa.answer_id) as total')
        )
        ->join('survey_answers AS sa','sa.answer_id','=','sqo.id')
        ->where('sqo.question_id', $question_id)
        ->groupBy(
            'sa.language',
            'sqo.id',
            'sqo.text'
        )
        ->get();
        
        return $records;
    }
    /**
     * Get the descriptiove question answers.
    */
    public static function getDescriptiveAnswers($question_id, $language)
    {
        $records = DB::table('survey_answers AS sa')
        ->select(
            'sa.language',
            DB::Raw('count(sa.question_id) as total')
        )
        ->where('sa.question_id', $question_id)
        ->where('sa.language', $language)
        ->groupBy(
            'sa.language'
        )
        ->first();

        return $records;
    }
    /**
     * Get the question options.
    */
    public static function getQuestionOptions($question_id)
    {
        $records = DB::table('survey_question_options')
        ->where('question_id', $question_id)
        ->get();

        return $records;
    }
    /**
     * Get a specific question option answers.
    */
    public static function getAnswerAmount($question_id, $answer_id, $language)
    {
        $records = DB::table('survey_question_options AS sqo')
        ->select(
            'sqo.text', 
            'sa.language',
            'sqo.id',
            DB::Raw('count(sa.answer_id) as total')
        )
        ->join('survey_answers AS sa','sa.answer_id','=','sqo.id')
        ->where('sqo.question_id', $question_id)
        ->where('sa.answer_id', $answer_id)
        ->where('sa.language', $language)
        ->groupBy(
            'sa.language',
            'sqo.id',
            'sqo.text'
        )
        ->first();
        
        return $records;
    }
}
