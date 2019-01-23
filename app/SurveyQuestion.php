<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
	/**
	 * Get the survey that owns the question.
	*/
    public function survey()
    {
        return $this->belongsTo('App\Survey');
    }

    /**
     * Get the options for the question.
    */
    public function options()
    {
        return $this->hasMany('App\SurveyQuestionOption','question_id');
    }

    /**
     * Get the published surveys questions.
    */
    public static function getPublishedQuestions()
    {
        $published_surveys = Survey::where('state', 'published')->get();
        $published_surveys_ids = array();
        foreach ($published_surveys as $published_survey) {
                $published_surveys_ids[] = $published_survey->id;
        }
        $published_questions = SurveyQuestion::whereIn('survey_id', $published_surveys_ids)->get();
        return $published_questions;
    }

}
