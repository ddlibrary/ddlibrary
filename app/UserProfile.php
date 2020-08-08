<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $user_id)
 */
class UserProfile extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
