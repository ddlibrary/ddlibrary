<?php

namespace App\Http\Controllers;

use App\Models\DownloadCount;
use App\Models\Resource;
use App\Models\TaxonomyTerm;
use App\Traits\GenderTrait;
use App\Traits\LanguageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LibraryAnalyticsController extends Controller
{
    use LanguageTrait, GenderTrait;

    public function index(Request $request): View
    {


        $languages = $this->getLanguages();

        $genders = $this->genders();

        $totalResources = Resource::groupBy('language')->select('language', DB::raw('count(*) as count'))->get();

        return view('admin.library-analytics.index', compact(['records', 'genders', 'languages', 'reportType', 'totalResources']));
    }
}
