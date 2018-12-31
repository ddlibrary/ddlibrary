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
}
