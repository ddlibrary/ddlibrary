<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Resource;
use App\UserProfile;
use App\Role;
use App\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
    
    public function index(Request $request)
    {
        $this->middleware('admin');

        $usersModel = new User();

        $users = $usersModel->filterUsers($request->all());
        $roles = Role::all();

        $request->session()->put('filters', $request->all());

        $filters = $request->session()->get('filters');

        return view('admin.users.users',compact('users','roles', 'filters'));
    }

    public function viewUser($userId)
    {
        $user = User::users()->where('id',$userId)->first();
        return view('users.view_user', compact('user'));
    }

    public function edit($userId)
    {
        $this->middleware('admin');
        $myResources = new Resource();
        $user = User::where('id',$userId)->first();
        $countries = $myResources->resourceAttributesList('taxonomy_term_data',15);
        $provinces = $myResources->resourceAttributesList('taxonomy_term_data',12);
        $userRoles = UserRole::where('user_id', $userId)->get();
        $roles = Role::all();
        return view('admin.users.edit_user', compact(
            'user',
            'countries',
            'provinces',
            'userRoles',
            'roles'
        ));    
    }

    public function update(Request $request, $userId)
    {
        $this->validate($request, [
            'username'      => 'required',
            'password'      => 'nullable',
            'email'         => 'required',
            'status'        => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'gender'        => 'required',
            'role'          => 'required',
            'phone'         => 'required',
            'country'       => 'required',
            'city'          => 'nullable',
        ]);

        //Saving contact info to the database
        $user = User::find($userId);
        $user->username = $request->input('username');
        if($request->filled('password')){
            $user->password = Hash::make($request->input('password'));
        }
        $user->email = $request->input('email');
        $user->status = $request->input('status');
        $user->save();

        $userProfile = UserProfile::where('user_id',$userId)->first();
        $userProfile->first_name = $request->input('first_name');
        $userProfile->last_name = $request->input('last_name');
        $userProfile->gender = $request->input('gender');
        $userProfile->country = $request->input('country');
        $userProfile->city = $request->input('city');
        $userProfile->phone = $request->input('phone');
        $userProfile->save();

        $userRole = UserRole::where('user_id', $userId)->first();
        if(count($userRole) == 0){
            $userRole = new UserRole();
            $userRole->user_id = $userId;
        }else{
            $userRole = $userRole;
        }
        $userRole->role_id = $request->input('role');
        $userRole->save();

        return redirect('/admin/user/edit/'.$userId)->with('success', 'User details updated successfully!');   
    }
}
