<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; 
use App\DownloadCount;
use App\ResourceView;
use App\Resource;
use Facebook\Facebook;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.analytics.analytics_main');
    }

    public function show(Request $request)
    {
        //if language is present in the request, otherwise default it to English
        $lang = $request->filled('language')?request('language'):"en";
        $date_from = $request->filled('date_from')?request('date_from'):"";
        $date_to = $request->filled('date_to')?request('date_to'):"";

        if($request->filled('type') && ($request->filled('source') && request('source') == "dd")) {
            $usersModel     = new User();
            $resourceModel  = new Resource();
            if(request('type') == "gender") {
                $totalUsersByGender     = $usersModel->totalUsersByGender($request);
                return view('admin.analytics.user_gender', compact('totalUsersByGender'));
            } else if (request('type') == "resource_download"){
                $downloadModel  = new DownloadCount();  
                $downloadCount  = $downloadModel->getCount();
                return view('admin.analytics.resource_download', compact('downloadCount')); 
            } else if (request('type') == "resource_view"){
                $viewModel = new ResourceView(); 
                $viewCount = $viewModel->getCount();
                return view('admin.analytics.resource_view', compact('viewCount')); 
            } else if (request('type') == "user_role"){
                $totalResourcesByRoles = $usersModel->totalResourcesByRoles();
                return view('admin.analytics.user_role', compact('totalResourcesByRoles')); 
            } else if (request('type') == "user_country"){
                $totalUsersByCountry = $usersModel->totalUsersByCountry();
                return view('admin.analytics.user_country', compact('totalUsersByCountry')); 
            } else if (request('type') == "resource_language"){
                $totalResources = $resourceModel->totalResourcesByLanguage();
                return view('admin.analytics.resource_language', compact('totalResources')); 
            } else if (request('type') == "resource_subject"){
                $totalResourcesBySubject = $resourceModel->totalResourcesBySubject($lang, $date_from, $date_to);
                return view('admin.analytics.resource_subject', compact('totalResourcesBySubject')); 
            } else if (request('type') == "resource_level"){
                $totalResourcesByLevel = $resourceModel->totalResourcesByLevel($lang);
                return view('admin.analytics.resource_level', compact('totalResourcesByLevel')); 
            } else if (request('type') == "resource_type"){
                $totalResourcesByType = $resourceModel->totalResourcesByType($lang);
                return view('admin.analytics.resource_type', compact('totalResourcesByType')); 
            } else if (request('type') == "resource_format"){
                $totalResourcesByFormat = $resourceModel->totalResourcesByFormat($lang);
                return view('admin.analytics.resource_format', compact('totalResourcesByFormat')); 
            } else if (request('type') == "download_count"){
                $downloadsCount = $resourceModel->downloadCounts($date_from, $date_to);
                return view('admin.analytics.resource_downloads_count', compact('downloadsCount')); 
            } 
        } else {
            return view('admin.analytics.analytics_main', compact('request'));
        }
    }
}
