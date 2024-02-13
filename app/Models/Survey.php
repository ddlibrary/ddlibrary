<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find(int $int)
 */
class Survey extends Model
{
    /**
     * Get the questions for the survey.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(\App\Models\SurveyQuestion::class);
    }
}
