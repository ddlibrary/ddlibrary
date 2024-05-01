<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SitewideAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $top10ViewedResources = $this->getTop10ViewedResources($request);
        return view('admin.analytics.sitewide.index', compact('top10ViewedResources'));
    }

    private function getTop10ViewedResources($request)
    {
        return Resource::select(['id', 'title'])
            ->withCount(['views' => function($query) use ($request){
                if($request->date_from && $request->date_to){
                    return $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
                };
            }])
            ->when($request->language, function ($query) use ($request) {
                return $query->whereLanguage($request->language);
            })
            ->orderBy('views_count', 'desc')
            ->limit(10)
            ->get();
    }
}
