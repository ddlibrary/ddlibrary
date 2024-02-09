<?php

namespace App\Http\Controllers;

use App;
use App\Models\Resource;
use App\Models\ResourceView;
use App\Models\TaxonomyTerm;
use Illuminate\View\View;

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
