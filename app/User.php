<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static find($user_id)
 *
 * @property mixed        username
 * @property mixed|string password
 * @property mixed        email
 * @property int|mixed    status
 * @property Carbon|mixed accessed_at
 * @property mixed        language
 * @property mixed        id
 * @property Carbon|mixed email_verified_at
 *
 * @method static get()
 * @method static where(string $string, $id)
 * @method static users()
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public function resource(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * The roles that belong to the user.
     */
    public function role(): HasOne
    {
        return $this->hasOne(UserRole::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * The user's profile
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the list of users to display as a table in the admin/users
     */
    public function scopeUsers($query): Collection
    {
        return DB::table('users')
            ->select(
                'users.id',
                'users.username',
                'users.password',
                'up.first_name',
                'up.last_name',
                'users.email',
                'users.status',
                'users.created_at',
                'users.accessed_at',
                'roles.name AS all_roles'
            )
            ->LeftJoin('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->LeftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
            ->LeftJoin('user_profiles AS up', 'up.user_id', '=', 'users.id')
            ->orderBy('accessed_at', 'desc')
            ->groupBy(
                'users.id',
                'users.username',
                'users.password',
                'up.first_name',
                'up.last_name',
                'users.accessed_at',
                'users.email',
                'users.status',
                'users.created_at',
                'roles.name'
            )
            ->get();
    }

    /**
     * Get the list of users to display as a table in the admin/users
     */
    public function filterUsers($requestArray): LengthAwarePaginator
    {
        return DB::table('users')
            ->select(
                'users.id',
                'users.username',
                'users.email',
                'users.status',
                'users.created_at',
                'users.accessed_at',
                'up.gender',
                'roles.name AS all_roles'
            )
            ->LeftJoin('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->LeftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
            ->LeftJoin('user_profiles AS up', 'up.user_id', '=', 'users.id')
            ->when(! empty($requestArray['username']), function ($query) use ($requestArray) {
                return $query
                    ->where('users.username', 'like', '%'.$requestArray['username'].'%');
            })
            ->when(! empty($requestArray['email']), function ($query) use ($requestArray) {
                return $query
                    ->where('users.email', 'like', '%'.$requestArray['email'].'%');
            })
            ->when(isset($requestArray['status']), function ($query) use ($requestArray) {
                return $query
                    ->where('users.status', $requestArray['status']);
            })
            ->when(isset($requestArray['role']), function ($query) use ($requestArray) {
                return $query
                    ->where('roles.id', $requestArray['role']);
            })
            ->when(isset($requestArray['gender']), function ($query) use ($requestArray) {
                return $query
                    ->where('up.gender', $requestArray['gender']);
            })
            ->when(isset($requestArray['country']), function ($query) use ($requestArray) {
                return $query
                    ->where('up.country', $requestArray['country']);
            })
            ->orderBy('accessed_at', 'desc')
            ->groupBy(
                'users.id',
                'users.username',
                'users.accessed_at',
                'users.email',
                'users.status',
                'up.gender',
                'users.created_at',
                'roles.name'
            )
            ->paginate(10);
    }

    public function oneUser($credentials): Model|Builder|null
    {
        return DB::table('users')
            ->select(
                'users.id',
                'users.username',
                'users.password',
                'users.email',
                'users.status',
                'users.created_at',
                'users.accessed_at'
            )
            ->where('email', $credentials['user-field'])
            ->orWhere('username', $credentials['user-field'])
            ->orWhere('id', $credentials['user-id'])
            ->first();
    }

    //Total users based on gender
    public function totalUsersByGender($request): Collection
    {
        $start = Carbon::parse($request->date_from)->startOfDay();  //2016-09-29 00:00:00.000000
        $end = Carbon::parse($request->date_to)->endOfDay(); //2016-09-29 23:59:59.000000

        return DB::table('users')
            ->select('user_profiles.gender')
            ->selectRaw('count(users.id) as total')
            ->when($request->filled('date_from') && $request->filled('date_to'), function ($query) use ($start, $end) {
                return $query->where('users.created_at', '>', $start)
                    ->where('users.created_at', '<', $end);
            })
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->groupBy('user_profiles.gender')
            ->get();
    }

    //Total users based on country
    public function totalUsersByCountry(): Collection
    {
        return DB::table('user_profiles AS up')
            ->select(
                'up.country AS id',
                DB::raw('count(up.country) as total')
            )
            ->groupBy(
                'up.country'
            )
            ->orderBy('id')
            ->get();
    }

    //Total users based on roles
    public function totalResourcesByRoles(): Collection
    {
        return DB::table('roles')
            ->select('roles.name', 'roles.id')
            ->selectRaw('count(roles.id) as total')
            ->join('user_roles', 'user_roles.role_id', '=', 'roles.id')
            ->groupBy('roles.id', 'roles.name')
            ->orderBy('total', 'DESC')
            ->get();
    }

    public function updateUser($newPassword, $credentials): int
    {
        return DB::table('users')
            ->where('email', $credentials['user-field'])
            ->orWhere('username', $credentials['user-field'])
            ->update($newPassword);
    }

    public function isAdministrator($userid): ?\stdClass
    {
        return DB::table('users')
            ->join('user_roles', 'user_roles.user_id', '=', 'users.id')
            ->where('users.id', $userid)
            ->where('user_roles.role_id', 5)
            ->first();
    }

    public function isNormalUser($userid): ?\stdClass
    {
        return DB::table('users')
            ->join('user_roles', 'user_roles.user_id', '=', 'users.id')
            ->where('users.id', $userid)
            ->where('user_roles.role_id', 2)
            ->first();
    }

    public function isLibraryManager($userid): ?\stdClass
    {
        return DB::table('users')
            ->join('user_roles', 'user_roles.user_id', '=', 'users.id')
            ->where('users.id', $userid)
            ->where('user_roles.role_id', 3)
            ->first();
    }
}
