<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, int $int)
 */
class SurveyQuestionOption extends Model
{
    /**
     * Get the question that owns the option.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SurveyQuestion::class);
    }

    public $timestamps = false;
}
