<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property mixed      user_id
 * @property mixed      first_name
 * @property mixed      last_name
 * @property mixed      country
 * @property mixed|null city
 * @property mixed      gender
 * @property mixed      phone
 *
 * @method static where(string $string, $user_id)
 * @method static findOrFail(int|null $id)
 */
class UserProfile extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $guarded = [];

    public function getUserProfile(array $credentials)
    {
        return DB::table('user_profiles')
            ->select(
                'user_id'
            )
            ->where('phone', $credentials['email'])
            ->first();
    }
}
