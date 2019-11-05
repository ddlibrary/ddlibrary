<?php

namespace App\Http\Controllers;

use Analytics;
use Spatie\Analytics\Period;

class ReportController extends Controller
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

    public function gaReport()
    {
        //retrieve visitors and pageview data for the current day and the last seven days
        $totalVisitorsAndPageViews  = Analytics::fetchTotalVisitorsAndPageViews(Period::days(30));
        $mostVisitedPages           = Analytics::fetchMostVisitedPages(Period::days(30), 10);
        $topReferrers               = Analytics::fetchTopReferrers(Period::days(30), 10);
        $userTypes                  = Analytics::fetchUserTypes(Period::days(30));
        $topBrowsers                = Analytics::fetchTopBrowsers(Period::days(30), 10);
        $topCountries               = Analytics::performQuery(Period::days(30), "ga:sessions");

        return view('admin.reports.reportsga', compact(
            'totalVisitorsAndPageViews',
            'mostVisitedPages',
            'topReferrers',
            'userTypes',
            'topBrowsers'
        ));
    }
}
