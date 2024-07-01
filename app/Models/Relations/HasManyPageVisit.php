<?php

namespace App\Models\Relations;

use App\Models\PageVisit;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPageVisit
{
    /**
     * Get user
     */
    public function pageVisits(): HasMany
    {
        return $this->hasMany(PageVisit::class);
    }
}
