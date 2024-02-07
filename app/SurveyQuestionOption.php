<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int $int)
 */
class SurveyQuestionOption extends Model
{
    /**
     * Get the question that owns the option.
     */
    public function question()
    {
        return $this->belongsTo(\App\SurveyQuestion::class);
    }

    public $timestamps = false;
}
