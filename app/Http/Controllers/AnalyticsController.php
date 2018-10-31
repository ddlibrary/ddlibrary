<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('admin.reports.analytics');
    }

    public function show()
    {
        return view('admin.reports.analytics');    
    }
}
