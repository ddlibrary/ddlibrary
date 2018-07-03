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

        $users = User::users();
        
        //if we have an API call
        if( $request->is('api/*')){
            return $users;
        }

        return view('admin.users',compact('users'));
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
