<?php

namespace App\Http\Controllers;
use App\News;
use App\Resource;

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
        $myResources = new Resource();
        //latest news for the homepage
        $latestNews         = News::listNews()->sortByDesc('created')->take(4);
        $subjectAreas = $myResources->subjectIconsAndTotal();

        return view('home', compact('latestNews','subjectAreas'));
    }
}
