<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestionOption extends Model
{

	/**
	 * Get the question that owns the option.
	*/
    public function question()
    {
        return $this->belongsTo('App\SurveyQuestion');
    }

    public $timestamps = false;
}
