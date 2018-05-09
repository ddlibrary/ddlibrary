<?php

namespace App\Http\Controllers;
use App\Menu;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index ()
    {
        $myMenu = new Menu();
        $menuRecords = $myMenu->Menu();
        return view('admin.menu.menu_list', compact('menuRecords'));
    }
}
