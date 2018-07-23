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
        $this->middleware('admin');
    }
    
    function index ()
    {
        $menuRecords = Menu::paginate(10);
        return view('admin.menu.menu_list', compact('menuRecords'));
    }
}
