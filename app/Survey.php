<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    /**
     * Get the questions for the survey.
     */
    public function questions()
    {
        return $this->hasMany('App\SurveyQuestion');
    }
}
