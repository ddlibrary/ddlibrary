<?php

namespace App\Http\Controllers;
use App\Page;

use Illuminate\Http\Request;

class PageController extends Controller
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
        $myPages = new Page();
        $pages = $myPages->listPages();
        return view('admin.pages.pages_list', compact('pages'));
    }
}
