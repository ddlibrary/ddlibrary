<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateProfileUserRequest;
use App\Models\Resource;
use App\Models\ResourceFavorite;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use BladeView;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laracsv\Export;

class UserController extends Controller
{
    /**
     * Showing the list of users in the admin panel
     *
     * @return BladeView|false|Application|Factory|View
     */
    public function index(Request $request): View
    {
        $this->middleware('admin');

        $usersModel = new User;

        $users = $usersModel->filterUsers($request->all());
        $roles = Role::all();

        $request->session()->put('filters', $request->all());

        $filters = $request->session()->get('filters');

        return view('admin.users.users', compact('users', 'roles', 'filters'));
    }

    /**
     * View a single user
     *
     * @return Application|BladeView|Factory|false|View
     */
    public function viewUser(): View
    {
        $user = User::users()->where('id', Auth::id())->first();

        $page = 'profile';

        return view('users.view_user', compact('page', 'user'));
    }

    /**
     * View a single users favorite resources
     *
     * @return Application|BladeView|Factory|false|View
     */
    public function favorites(): View
    {
        $user = User::users()->where('id', Auth::id())->first();
        $favorites = ResourceFavorite::where('user_id', Auth::id())->pluck('resource_id');
        $resources = Resource::find($favorites);

        $page = 'favorites';

        return view('users.favorites', compact('user', 'page', 'resources'));
    }

    /**
     * View a single users uploaded resources
     *
     * @return Application|BladeView|Factory|false|View
     */
    public function uploadedResources(): View
    {
        $user = User::users()->where('id', Auth::id())->first();
        $resources = Resource::where('user_id', Auth::id())->get();

        $page = 'uploaded-resources';

        return view('users.uploaded-resources', compact('user', 'page', 'resources'));
    }

    /**
     * Update user profile
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function updateProfile(UpdateProfileUserRequest $request): RedirectResponse
    {

        $user = User::find(Auth::id());

        $userEmail = $user->email;
        $user->username = $request->input('username');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($userEmail != $request->input('email')) {
            $user->email_verified_at = null;
            $user->save();
            event(new Registered($user));
        } else {
            $user->save();
        }

        Session::flash('alert', [
            'message' => __('Your data has been updated successfully.'),
            'level' => 'success',
        ]);

        return redirect(URL('user/profile'));
    }

    /**
     * Edit a user details
     *
     * @return Application|BladeView|Factory|false|View
     */
    public function edit($userId): View
    {
        $this->middleware('admin');
        $myResources = new Resource;
        $user = User::where('id', $userId)->first();
        $countries = $myResources->resourceAttributesList('taxonomy_term_data', 15);
        $provinces = $myResources->resourceAttributesList('taxonomy_term_data', 12);
        $userRoles = UserRole::where('user_id', $userId)->get();
        $roles = Role::all();

        return view('admin.users.edit_user', compact('user', 'countries', 'provinces', 'userRoles', 'roles'));
    }

    /**
     * Edit a user details
     *
     *
     * @return Application|Redirector|RedirectResponse
     *
     * @throws ValidationException
     */
    public function update(UpdateUserRequest $request, $userId): RedirectResponse
    {

        if ($request->filled('city')) {
            $city = $request->input('city');
        } elseif ($request->filled('city_other')) {
            $city = $request->input('city_other');
        } else {
            $city = null;
        }

        //Saving contact info to the database
        $user = User::find($userId);
        $user->username = $request->input('username');
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->email = $request->input('email');
        $user->status = $request->input('status');
        $user->save();

        $userProfile = UserProfile::where('user_id', $userId)->first();
        $userProfile->first_name = $request->input('first_name');
        $userProfile->last_name = $request->input('last_name');
        $userProfile->gender = $request->input('gender');
        $userProfile->country = $request->input('country');
        $userProfile->city = $city;
        $userProfile->phone = $request->input('phone');
        $userProfile->save();

        $userRole = UserRole::where('user_id', $userId);
        $userRole->delete();

        $userRole = new UserRole;
        $userRole->user_id = $userId;
        $userRole->role_id = $request->input('role');
        $userRole->save();

        return redirect('/admin/user/edit/'.$userId)->with('success', 'User details updated successfully!');
    }

    /**
     * Delete a user
     */
    public function deleteUser($userId): RedirectResponse
    {
        $user = User::find($userId);
        $user->delete();

        return back()->with('error', 'You deleted the record!');
    }

    /**
     * Export the user list to CSV
     */
    public function exportUsers()
    {
        $users = User::get(); // All users
        // $userProfiles = UserProfile::with('first_name','last_name')->get();
        $csvExporter = new Export;
        $csvExporter
            ->build($users, [
                'email' => 'Email Address',
                'profile.first_name' => 'First Name',
                'profile.last_name' => 'Last Name',
            ])
            ->download();
    }
}
