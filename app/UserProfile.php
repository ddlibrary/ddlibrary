<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed      user_id
 * @property mixed      first_name
 * @property mixed      last_name
 * @property mixed      country
 * @property mixed|null city
 * @property mixed      gender
 * @property mixed      phone
 * @method static find($userId)
 * @method static where(string $string, $userId)
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
