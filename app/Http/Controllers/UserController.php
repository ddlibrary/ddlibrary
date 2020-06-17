<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\User;
use App\Resource;
use App\UserProfile;
use App\Role;
use App\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laracsv\Export;

class UserController extends Controller
{
    /**
     * Showing the list of users in the admin panel
     *
     * @return \Illuminate\Http\Response
     */   
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

    /**
     * View a single user
     *
     * @return \Illuminate\Http\Response
     */
    public function viewUser()
    {
        $user = User::users()->where('id', Auth::id())->first();
        return view('users.view_user', compact('user'));
    }

    /**
     * Update user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'username' => 'required',
        ]);

        $user = User::find(Auth::id());
        $user->username = $request->input('username');
        $user->email = $request->input('email');

        if($request->filled('password')){
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect(URL('user/profile'));
    }

    /**
     * Edit a user details
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Edit a user details
     *
     * @return \Illuminate\Http\Response
     */
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

        if($request->filled('city')){
            $city = $request->input('city');
        }elseif($request->filled('city_other')){
            $city = $request->input('city_other');
        }else{
            $city = NULL;
        }

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
        $userProfile->city = $city;
        $userProfile->phone = $request->input('phone');
        $userProfile->save();

        $userRole = UserRole::where('user_id', $userId);
        $userRole->delete();

        $userRole = new UserRole();
        $userRole->user_id = $userId;
        $userRole->role_id = $request->input('role');
        $userRole->save();

        return redirect('/admin/user/edit/'.$userId)->with('success', 'User details updated successfully!');   
    }

    /**
     * Delete a user
     *
     * @param $userId
     *
     * @return RedirectResponse
     */
    public function deleteUser($userId)
    {
        // Since we have not included an onDelete('cascade'),
        // we have to find the corresponding records from
        // UserProfile and UserRole and delete them manually.
        // We don't however, want to delete the resources they've
        // uploaded so, we set null.
        $userProfile = UserProfile::where('user_id', $userId);
        if ($userProfile != null){
            $userProfile->delete();
        }
        $userRole = UserRole::where('user_id', $userId);
        if ($userRole != null){
            $userRole->delete();
        }

        User::find($userId)->delete();
        return back()->with('error', 'You deleted the record!');
    }

    /**
    * Export the user list to CSV
    */
    public function exportUsers()
    {
        $users = User::get(); // All users
        //$userProfiles = UserProfile::with('first_name','last_name')->get();
        $csvExporter = new Export();
        $csvExporter->build($users, [
            'email' =>'Email Address', 
            'profile.first_name' => 'First Name', 
            'profile.last_name' => 'Last Name'
        ])->download();
    }
}
