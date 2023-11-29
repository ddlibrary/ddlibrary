<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(int $int)
 */
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
