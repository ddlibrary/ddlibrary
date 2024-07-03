<?php

namespace App\Models\Relations;

use App\Models\PageView;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPageView
{
    /**
     * Get user
     */
    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class);
    }
}
