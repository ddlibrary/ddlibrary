<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed     user_id
 * @property int|mixed role_id
 *
 * @method static where(string $string, $userId)
 */
class UserRole extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The users that belong to the role.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
