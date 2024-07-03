<?php

namespace App\Models\Relations;

use App\Models\SitewidesPageView;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManySitewidesPageView
{
    /**
     * Get user
     */
    public function sitewidesPageViews(): HasMany
    {
        return $this->hasMany(SitewidesPageView::class);
    }
}
