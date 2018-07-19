<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResourceFlag;

class FlagController extends Controller
{
    public function index()
    {
        $flags = ResourceFlag::paginate(10);
        return view('admin.flags.flags_list', compact('flags'));
    }
}
