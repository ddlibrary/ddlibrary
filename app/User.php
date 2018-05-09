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
                'users.id',
                'users.name',
                'users.password', 
                'users.email',
                'users.status', 
                'users.created',
                'users.access',
                DB::raw('group_concat(roles.name) AS all_roles'
            ))
            ->join('users_roles', 'users.id', '=', 'users_roles.userid')
            ->join('roles', 'roles.roleid', '=', 'users_roles.roleid')
            ->orderBy('access','desc')
            ->groupBy(
                'users.id',
                'users.name',
                'users.password',
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
                    ->selectRaw('count(users.id) as totalUsers')
                    ->count();
        return $records;
    }

    //Total users based on gender
    public function totalUsersByGender()
    {
        $records = DB::table('users')
                    ->select('users_profiles.gender')
                    ->selectRaw('count(users.id) as total')
                    ->join('users_profiles','users_profiles.userid','=','users.id')
                    ->groupBy('users_profiles.gender')
                    ->get();
        return $records;   
    }

    //Total users based on country
    public function totalUsersByCountry()
    {
        $records = DB::table('users_profiles')
                    ->select('country')
                    ->selectRaw('count(country) as total')
                    ->groupBy('country')
                    ->orderBy('total','DESC')
                    ->get();
        return $records;   
    }

    //Total users based on roles
    public function totalResourcesByRoles()
    {
        $records = DB::table('roles')
                    ->select('roles.name')
                    ->selectRaw('count(roles.roleid) as total')
                    ->join('users_roles','users_roles.roleid','=','roles.roleid')
                    ->groupBy('roles.roleid', 'roles.name')
                    ->orderBy('total','DESC')
                    ->get();
        return $records;   
    }

    public function updateUser($newPassword, $email)
    {
        DB::table('users')
            ->where('email',$email)
            ->update($newPassword);
    }
}
