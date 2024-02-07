<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\ResourceFlag;

class FlagController extends Controller
{
    public function index(): View
    {
        $flags = ResourceFlag::paginate(10);

        return view('admin.flags.flags_list', compact('flags'));
    }
}
