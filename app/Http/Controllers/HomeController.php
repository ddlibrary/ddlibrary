<?php

namespace App\Http\Controllers;
use App\News;
use App\Resource;
use Config;

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
    public function index(Request $request)
    {
        $resources = new Resource();
        //setting the search session empty
        $request->session()->forget('search');
        //latest news for the homepage
        $latestNews         = News::where('language',Config::get('app.locale'))->orderBy('id','desc')->take(4)->get();
        $subjectAreas       = $resources->subjectIconsAndTotal();
        $featured           = $resources->featuredCollections();
        $latestResources    = Resource::published()->where('language',Config::get('app.locale'))->orderBy('id','desc')->take(4)->get();
        return view('home', compact('latestNews','subjectAreas','featured','latestResources'));
    }
}
