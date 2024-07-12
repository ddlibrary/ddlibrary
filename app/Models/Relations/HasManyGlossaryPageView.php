<?php

namespace App\Models\Relations;

use App\Models\GlossaryPageView;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyGlossaryPageView
{
    /**
     * Get user
     */
    public function glossaryPageViews(): HasMany
    {
        return $this->hasMany(GlossaryPageView::class);
    }
}
