<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Page;
use App\Models\Resource;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        DDLClearSession();

        // total users in number for the dashboard
        $totalUsers = User::count();
        // latest users for the dashboard
        $latestUsers = User::orderBy('id', 'desc')->take(5)->get();
        // total resources in number for the dashboard
        $totalResources = Resource::count();
        // latest resources for the dashboard
        $latestResources = Resource::orderBy('id', 'desc')->take(5)->get();
        $totalNews = News::count();
        // latest news for the dashboard
        $latestNews = News::orderBy('id', 'desc')->take(5)->get();
        $totalPages = Page::count();
        // latest pages for the dashboard
        $latestPages = Page::orderBy('id', 'desc')->take(5)->get();

        return view('admin.main', compact(
            'totalUsers',
            'latestUsers',
            'totalResources',
            'latestResources',
            'totalNews',
            'latestNews',
            'totalPages',
            'latestPages'
        ));
    }
}
