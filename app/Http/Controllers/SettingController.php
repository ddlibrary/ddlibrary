<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    function index ()
    {
        return view('admin.settings.settings_view');
    }
}
