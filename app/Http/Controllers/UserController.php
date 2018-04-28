<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::users();
        return view('admin.users',compact('users'));
    }

    public function viewUser($userId)
    {
        $user = User::users()->where('userid',$userId)->first();
        return view('admin.users.view_user', compact('user'));
    }
}
