<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\DownloadCount;
use App\Resource;
use App\ResourceView;
use App\User;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.analytics.analytics_main');
    }

    public function show(Request $request): View
    {
        //if language is present in the request, otherwise default it to English
        $lang = $request->filled('language') ? request('language') : 'en';
        $date_from = $request->filled('date_from') ? request('date_from') : '';
        $date_to = $request->filled('date_to') ? request('date_to') : '';

        if ($request->filled('type')) {
            $usersModel = new User();
            $resourceModel = new Resource();
            if (request('type') == 'gender') {
                $totalUsersByGender = $usersModel->totalUsersByGender($request);

                return view('admin.analytics.user_gender', compact('totalUsersByGender'));
            } elseif (request('type') == 'resource_download') {
                $downloadModel = new DownloadCount();
                $downloadCount = $downloadModel->getCount();

                return view('admin.analytics.resource_download', compact('downloadCount'));
            } elseif (request('type') == 'resource_view') {
                $viewModel = new ResourceView();
                $viewCount = $viewModel->getCount();

                return view('admin.analytics.resource_view', compact('viewCount'));
            } elseif (request('type') == 'user_role') {
                $totalResourcesByRoles = $usersModel->totalResourcesByRoles();

                return view('admin.analytics.user_role', compact('totalResourcesByRoles'));
            } elseif (request('type') == 'user_country') {
                $totalUsersByCountry = $usersModel->totalUsersByCountry();

                return view('admin.analytics.user_country', compact('totalUsersByCountry'));
            } elseif (request('type') == 'resource_language') {
                $totalResources = $resourceModel->totalResourcesByLanguage();

                return view('admin.analytics.resource_language', compact('totalResources'));
            } elseif (request('type') == 'resource_subject') {
                $totalResourcesBySubject = $resourceModel->totalResourcesBySubject($lang, $date_from, $date_to);

                return view('admin.analytics.resource_subject', compact('totalResourcesBySubject'));
            } elseif (request('type') == 'resource_level') {
                $totalResourcesByLevel = $resourceModel->totalResourcesByLevel($lang);

                return view('admin.analytics.resource_level', compact('totalResourcesByLevel'));
            } elseif (request('type') == 'resource_type') {
                $totalResourcesByType = $resourceModel->totalResourcesByType($lang);

                return view('admin.analytics.resource_type', compact('totalResourcesByType'));
            } elseif (request('type') == 'resource_format') {
                $totalResourcesByFormat = $resourceModel->totalResourcesByFormat($lang);

                return view('admin.analytics.resource_format', compact('totalResourcesByFormat'));
            } elseif (request('type') == 'download_count') {
                $downloadsCount = $resourceModel->downloadCounts($date_from, $date_to);

                return view('admin.analytics.resource_downloads_count', compact('downloadsCount'));
            }
        } else {
            return view('admin.analytics.analytics_main', compact('request'));
        }
    }
}
