<?php

namespace App\Http\Controllers;
use App\Page;

use Illuminate\Http\Request;

class PageController extends Controller
{
    function index ()
    {
        $myPages = new Page();
        $pages = $myPages->listPages();
        return view('admin.pages.pages_list', compact('pages'));
    }
}
