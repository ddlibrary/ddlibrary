<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    /**
     * Get the list of users to display as a table in the admin/users
     */
    public function scopeUsers()
    {
        $users = DB::table('users')
            ->select(
                'users.userid',
                'users.name', 
                'users.email',
                'users.status', 
                'users.created',
                'users.access',
                DB::raw('group_concat(roles.name) AS all_roles'
            ))
            ->join('users_roles', 'users.userid', '=', 'users_roles.userid')
            ->join('roles', 'roles.roleid', '=', 'users_roles.roleid')
            ->orderBy('access','desc')
            ->groupBy(
                'users.userid',
                'users.name',
                'users.access',
                'users.email',
                'users.status',
                'users.created'
            )
            ->paginate(50);

        return $users;
    }

    /**
     * Get the total users available in the system
     */
    public function totalUsers()
    {
        $records = DB::table('users')
                    ->selectRaw('count(users.userid) as totalUsers')
                    ->count();
        return $records;
    }


    //Total users based on gender
    public function totalUsersByGender()
    {
        $records = DB::table('users')
                    ->select('users_profiles.gender')
                    ->selectRaw('count(users.userid) as total')
                    ->join('users_profiles','users_profiles.userid','=','users.userid')
                    ->groupBy('users_profiles.gender')
                    ->get();
        return $records;   
    }
}
