<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\SitewidePageView;
use App\Models\TaxonomyTerm;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class ImpactController extends Controller
{
    public function index(): View
    {
        $totalResources = Resource::count();
        $totalSubjects = TaxonomyTerm::where('vid', 8)->where('language', App::getLocale())->count();
        $monthlyViews = SitewidePageView::where('created_at', '>', \Carbon\Carbon::now()->subDays(30))->where('is_bot', false)->count();

        return view('impact.impact_page', compact('totalResources', 'monthlyViews', 'totalSubjects'));
    }
}
