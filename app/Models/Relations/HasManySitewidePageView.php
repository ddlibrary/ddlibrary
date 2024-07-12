<?php

namespace App\Models\Relations;

use App\Models\SitewidePageView;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManySitewidePageView
{
    /**
     * Get user
     */
    public function sitewidePageViews(): HasMany
    {
        return $this->hasMany(SitewidePageView::class);
    }
}
