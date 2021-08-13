<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int $int)
 */
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
    public static function getPublishedQuestions($language=Null)
    {
        $published_surveys = Survey::where('state', 'published')->get();
        $published_surveys_ids = array();
        foreach ($published_surveys as $published_survey) {
                $published_surveys_ids[] = $published_survey->id;
        }
        $published_questions = SurveyQuestion::whereIn('survey_id', $published_surveys_ids)->where('language', $language)->get();
        return $published_questions;
    }

}
