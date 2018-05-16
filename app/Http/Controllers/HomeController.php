<?php

namespace App\Http\Controllers;
use App\News;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //latest news for the homepage
        $latestNews         = News::listNews()->sortByDesc('created')->take(4);
        return view('home', compact('latestNews'));
    }
}
