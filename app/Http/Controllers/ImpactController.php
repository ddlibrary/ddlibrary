<?php

namespace App\Http\Controllers;

use App;
use App\Jobs\CalculateMonthlyViews;
use App\Models\Resource;
use App\Models\TaxonomyTerm;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ImpactController extends Controller
{
    public function index($update = null): View
    {
        $totalResources = Resource::count();
        $totalSubjects = TaxonomyTerm::where('vid', 8)->where('language', App::getLocale())->count();
        $monthlyViews = null;
        $monthlyViewsTimestamp = null;
        if (Cache::get('monthlyViews')) {
            $monthlyViews = Cache::get('monthlyViews');
            $monthlyViewsTimestamp = Cache::get('monthlyViewsTimestamp');
        } elseif (! $update) {
            CalculateMonthlyViews::dispatch();
        }
        if ($update && isAdmin()) {
            CalculateMonthlyViews::dispatch();
            Session::flash('alert', [
                'message' => __('Your request has been submitted. Please check back in a few minutes.'),
                'level' => 'success',
            ]);

            return view('impact.impact_page', compact('totalResources', 'monthlyViews', 'monthlyViewsTimestamp', 'totalSubjects'));
        }

        return view('impact.impact_page', compact('totalResources', 'monthlyViews', 'monthlyViewsTimestamp', 'totalSubjects'));
    }
}
