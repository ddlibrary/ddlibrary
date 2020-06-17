<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed     user_id
 * @property int|mixed role_id
 * @method static find($userId)
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
