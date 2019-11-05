<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use App\ResourceView;
use App\TaxonomyTerm;
use Analytics;
use Spatie\Analytics\Period;

class ImpactController extends Controller
{
    public function index()
    {
        //total resources in number for the dashboard
        $totalResources     = Resource::count();
        $myresourceView     = new ResourceView;
        $totalSubjects      = TaxonomyTerm::where('vid',8)->count();
        $monthlyViews       = $myresourceView->where('created_at', '>', \Carbon\Carbon::now()->subDays(30))->count();
        return view('impact.impact_page', compact('totalResources','monthlyViews','totalSubjects'));
    }
}
