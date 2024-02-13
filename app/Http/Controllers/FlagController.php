<?php

namespace App\Http\Controllers;

use App\Models\ResourceFlag;
use Illuminate\View\View;

class FlagController extends Controller
{
    public function index(): View
    {
        $flags = ResourceFlag::paginate(10);

        return view('admin.flags.flags_list', compact('flags'));
    }
}
