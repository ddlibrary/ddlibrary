<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App;
use App\Resource;
use App\ResourceView;
use App\TaxonomyTerm;

class ImpactController extends Controller
{
    public function index(): View
    {
        //total resources in number for the dashboard
        $totalResources = Resource::count();
        $myresourceView = new ResourceView;
        $totalSubjects = TaxonomyTerm::where('vid', 8)->where('language', App::getLocale())->count();
        $monthlyViews = $myresourceView->where('created_at', '>', \Carbon\Carbon::now()->subDays(30))->whereNotNull('platform')->count();

        return view('impact.impact_page', compact('totalResources', 'monthlyViews', 'totalSubjects'));
    }
}
