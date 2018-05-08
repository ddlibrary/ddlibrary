<?php

namespace App\Http\Controllers;
use App\Menu;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    function index ()
    {
        $myMenu = new Menu();
        $menuRecords = $myMenu->Menu();
        return view('admin.menu.menu_list', compact('menuRecords'));
    }
}
