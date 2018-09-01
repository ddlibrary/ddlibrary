<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use App\User;
use App\DownloadCount;
use App\ResourceView;
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
    
    public function index()
    {
        //setting the search session empty
        DDLClearSession();
        
        $resourceModel  = new Resource();
        $usersModel     = new User();
        $downloadModel  = new DownloadCount();

        $downloadCount = $downloadModel->getCount();

        //total resources by language
        $totalResources             = $resourceModel->totalResourcesByLanguage();
        $totalResourcesBySubject    = $resourceModel->totalResourcesBySubject();
        $totalResourcesByLevel      = $resourceModel->totalResourcesByLevel();
        $totalResourcesByType       = $resourceModel->totalResourcesByType();
        $totalResourcesByFormat     = $resourceModel->totalResourcesByFormat();
        $totalUsersByGender         = $usersModel->totalUsersByGender();
        $totalUsersByCountry        = $usersModel->totalUsersByCountry();
        $totalResourcesByRoles      = $usersModel->totalResourcesByRoles();

        return view('admin.reports.reports', compact(
            'totalResources',
            'totalUsersByGender',
            'totalResourcesBySubject',
            'totalResourcesByLevel',
            'totalResourcesByType',
            'totalResourcesByFormat',
            'totalUsersByCountry',
            'totalResourcesByRoles',
            'downloadCount'
        ));
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
