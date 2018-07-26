<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

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
        $roles = $usersModel->rolesList();

        $request->session()->put('filters', $request->all());

        $filters = $request->session()->get('filters');

        return view('admin.users.users',compact('users','roles', 'filters'));
    }

    public function viewUser($userId)
    {
        $user = User::users()->where('id',$userId)->first();
        return view('users.view_user', compact('user'));
    }

    public function updateUser($userId)
    {
        $this->middleware('admin');
        $user = User::users()->where('id',$userId)->first();
        return view('admin.users.update_user', compact('user'));    
    }
}
