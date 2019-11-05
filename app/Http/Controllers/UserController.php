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
use DB;
class UserController extends Controller
{

    public function index(Request $request)
    {
        $this->middleware('admin');

    $usersModel = new User();
    $users = $usersModel->filterUsers($request->all());
    $roles = Role::all();
    $request->session()->put('filters', $request->all());
    $filters = $request->session()->get('filters');

    return view('admin.users.users', compact('users', 'roles', 'filters'));
    }
    public function viewUser()
    {
        $user = User::users()->where('id', Auth::id())->first();
        return view('users.view_user', compact('user'));
    }
    public function create()
    {
        
        DDLClearSession();
        $myResources = new Resource();
        $countries = $myResources->resourceAttributesList('taxonomy_term_data', 15);
        $provinces = $myResources->resourceAttributesList('taxonomy_term_data', 12)->all();
        return view('users.user_create', compact('countries', 'provinces'));
    }
    public function edit($userId)
    {
        $this->middleware('admin');
        $myResources = new Resource();
        $user = User::where('id', $userId)->first();
        $countries = $myResources->resourceAttributesList('taxonomy_term_data', 15);
        $provinces = $myResources->resourceAttributesList('taxonomy_term_data', 12);
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
    public function exportUsers()
    {
        $users = User::get(); // All users
        //$userProfiles = UserProfile::with('first_name','last_name')->get();
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build($users, [
            'email' => 'Email Address',
            'profile.first_name' => 'First Name',
            'profile.last_name' => 'Last Name'
        ])->download();
    }
    /* the bellow functions will work with api access  */
    /* show users list */
    public function listUsers()
    {
        $show_user = User::join('user_roles', 'users.id', 'user_roles.user_id')->where('user_roles.user_id', '!=', 'users.id')->get();
        return UserResource::collection($show_user);
    }
    // inserting user to db
    public function insert_user(Request $request)
    {
        $save_user = new User();
        $save_user->username = $request['UserName'];
        $save_user->email = $request['Email'];
        $save_user->password = bcrypt($request['Password']);
        $save_user->status = $request['Status'];
        $save_user->save();
        //  current saved user id 
        $c_id = DB::table('users')->orderBy('id', 'desc')->first();
        DB::table('user_roles')->insert([
            'user_id' => $c_id->id,
            'role_id' => $request['Role']
        ]);
        DB::table('user_profiles')->insert([
            'user_id' => $c_id->id,
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'phone' => $request['phone'],
            'gender' => $request['gender'],
            'country' => $request['country'],
            'city' => $request['city']
        ]);
        return response()->json($save_user);
    }
    // this function delete the user from list
    public function user_delete($id)
    {
        $find_user = User::find($id);
        // find the photo and remove from directory
        $oldPhoto = $find_user->photo;
        if ($oldPhoto !== $find_user->photo) {
            $this->removeUserphoto($oldPhoto);
        }
        $find_user->delete();
        return response()->json($find_user);
    }
    // this function reterive single user
    public function user_get($id)
    {
        $find_user_edit = User::find($id);
        return response()->json($find_user_edit);
    }
    // this function update the user which reterived
    public function user_update(Request $request, $id)
    {
        $find_user_update = User::find($id);
        if ($request->filled('city')) {
            $city = $request->input('city');
        } elseif ($request->filled('city_other')) {
            $city = $request->input('city_other');
        } else {
            $city = NULL;
        }
        $find_user_update->username = $request['username'];
        $find_user_update->email = $request['email'];
        if ($request->filled('password')) {
            $find_user_update->password = Hash::make($request->input('password'));
        }
        $find_user_update->password = bcrypt($request['password']);
        $find_user_update->status = $request['status'];
        $userProfile = UserProfile::where('user_id', $id)->first();
        $userProfile->first_name = $request->input('first_name');
        $userProfile->last_name = $request->input('last_name');
        $userProfile->gender = $request->input('gender');
        $userProfile->country = $request->input('country');
        $userProfile->city = $city;
        $userProfile->phone = $request->input('phone');
        $userProfile->save();
        $userRole = UserRole::where('user_id', $id);
        $userRole->delete();
        $userRole = new UserRole();
        $userRole->user_id = $id;
        $userRole->role_id = $request->input('role');
        $userRole->save();
        $find_user_update->save();
        return response()->json($find_user_update);
    }
}