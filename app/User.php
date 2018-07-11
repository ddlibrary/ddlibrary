<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    /**
     * Get the list of users to display as a table in the admin/users
     */
    public function scopeUsers($query)
    {
        $users = DB::table('users')
            ->select(
                'users.id',
                'users.username',
                'users.password',
                'up.first_name',
                'up.last_name',
                'users.email',
                'users.status', 
                'users.created',
                'users.access',
                DB::raw('group_concat(roles.name) AS all_roles'
            ))
            ->LeftJoin('users_roles', 'users.id', '=', 'users_roles.userid')
            ->LeftJoin('roles', 'roles.roleid', '=', 'users_roles.roleid')
            ->LeftJoin('users_profiles AS up', 'up.userid', '=', 'users.id')
            ->orderBy('access','desc')
            ->groupBy(
                'users.id',
                'users.username',
                'users.password',
                'up.first_name',
                'up.last_name',
                'users.access',
                'users.email',
                'users.status',
                'users.created'
            )
            ->get();

        return $users;
    }

    /**
     * Get the list of users to display as a table in the admin/users
     */
    public function filterUsers($requestArray)
    {
        $users = DB::table('users')
            ->select(
                'users.id',
                'users.username',
                'users.email',
                'users.status', 
                'users.created',
                'users.access',
                'up.gender',
                DB::raw('group_concat(roles.name) AS all_roles'
            ))
            ->LeftJoin('users_roles', 'users.id', '=', 'users_roles.userid')
            ->LeftJoin('roles', 'roles.roleid', '=', 'users_roles.roleid')
            ->LeftJoin('users_profiles AS up', 'up.userid', '=', 'users.id')
            ->when(!empty($requestArray['username']), function($query) use($requestArray){
                return $query
                    ->where('users.username', 'like', '%'.$requestArray['username'].'%');
            })
            ->when(!empty($requestArray['email']), function($query) use($requestArray){
                return $query
                    ->where('users.email', 'like', '%'.$requestArray['email'].'%');
            })
            ->when(isset($requestArray['status']), function($query) use($requestArray){
                return $query
                    ->where('users.status', $requestArray['status']);
            })
            ->when(isset($requestArray['role']), function($query) use($requestArray){
                return $query
                    ->where('roles.roleid', $requestArray['role']);
            })  
            ->when(isset($requestArray['gender']), function($query) use($requestArray){
                return $query
                    ->where('up.gender', $requestArray['gender']);
            })
            ->when(isset($requestArray['country']), function($query) use($requestArray){
                return $query
                    ->where('up.country', $requestArray['country']);
            })
            ->orderBy('access','desc')
            ->groupBy(
                'users.id',
                'users.username',
                'users.access',
                'users.email',
                'users.status',
                'up.gender',
                'users.created'
            )
            ->paginate(10);

        return $users;
    }

    public function oneUser($credentials)
    {
        $user = DB::table('users')
            ->select(
                'users.id',
                'users.username',
                'users.password', 
                'users.email',
                'users.status', 
                'users.created',
                'users.access'
            )
            ->where('email', $credentials['user-field'])
            ->orWhere('username', $credentials['user-field'])
            ->first();

        return $user;    
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
                    ->select('roles.name', 'roles.roleid')
                    ->selectRaw('count(roles.roleid) as total')
                    ->join('users_roles','users_roles.roleid','=','roles.roleid')
                    ->groupBy('roles.roleid', 'roles.name')
                    ->orderBy('total','DESC')
                    ->get();
        return $records;   
    }

    public function updateUser($newPassword, $credentials)
    {
        return DB::table('users')
            ->where('email',$credentials['user-field'])
            ->orWhere('username',$credentials['user-field'])
            ->update($newPassword);
    }

    public function isAdministrator($userid)
    {
        return DB::table('users')
            ->join('users_roles', 'users_roles.userid','=','users.id')
            ->where('users.id',$userid)
            ->where('users_roles.roleid', 5)
            ->first();
    }

    public function createUser($data)
    {
        return DB::table('users')->insertGetId([
            'username'      => $data['username'],
            'password'      => Hash::make($data['password']),
            'email'         => $data['email'],
            'created'       => \Carbon\Carbon::now()->timestamp,
            'updated'       => \Carbon\Carbon::now()->timestamp
        ]);
    }

    public function createUserProfile($userId, $data)
    {
        return DB::table('users_profiles')->insert([
            'userid'        => $userId,
            'first_name'    => $data['first_name'], 
            'last_name'     => $data['last_name'],
            'country'       => $data['country'],
            'province'      => $data['city'],
            'gender'        => $data['gender'],
            'age'           => $data['age'],
            'created'       => \Carbon\Carbon::now()->timestamp,
            'updated'       => \Carbon\Carbon::now()->timestamp
        ]);
    }

    public function rolesList()
    {
        $records = DB::table('roles')
                    ->select(
                        'roleid',
                        'name'
                    )
                    ->get();
        return $records; 
    }
}
