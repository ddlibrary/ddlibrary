<?php

namespace App\Http\Controllers;

use App;
use App\Models\Resource;
use App\Models\SitewidePageView;
use App\Models\TaxonomyTerm;
use Illuminate\View\View;

class ImpactController extends Controller
{
    public function index(): View
    {
        $totalResources = Resource::count();
        $totalSubjects = TaxonomyTerm::where('vid', 8)->where('language', App::getLocale())->count();
        $monthlyViews = SitewidePageView::where('created_at', '>', \Carbon\Carbon::now()->subDays(30))
            ->where(function($views)  {
                $views->where(function($query) {
                    $query->where('is_bot', false);
                })
                    ->where(function($query) {
                        $query->where('browser', '!=', 'Mozilla')
                            ->where('platform_id', '!=', 0);
                    });
            })
            ->count();

        return view('impact.impact_page', compact('totalResources', 'monthlyViews', 'totalSubjects'));
    }
}
