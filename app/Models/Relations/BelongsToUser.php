<?php

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    /**
     * Get user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
