<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Flag;

class FlagController extends Controller
{
    public function index()
    {
        $flags = Flag::flags();
        return view('admin.flags.flags_list', compact('flags'));
    }
}
