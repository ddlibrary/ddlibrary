<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, int $int)
 */
class SurveyQuestion extends Model
{

    use HasFactory;
    /**
     * Get the survey that owns the question.
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Survey::class);
    }

    /**
     * Get the options for the question.
     */
    public function options(): HasMany
    {
        return $this->hasMany(\App\Models\SurveyQuestionOption::class, 'question_id');
    }

    /**
     * Get the published surveys questions.
     */
    public static function getPublishedQuestions($language = null)
    {
        $published_surveys = Survey::where('state', 'published')->get();
        $published_surveys_ids = [];
        foreach ($published_surveys as $published_survey) {
            $published_surveys_ids[] = $published_survey->id;
        }
        $published_questions = SurveyQuestion::whereIn('survey_id', $published_surveys_ids)->where('language', $language)->get();

        return $published_questions;
    }
}
