<?php

namespace App\Http\Controllers;

use App\Enums\LanguageEnum;
use App\Models\Browser;
use App\Models\Device;
use App\Models\GlossaryPageView;
use App\Models\GlossarySubject;
use App\Models\Platform;
use App\Traits\GenderTrait;
use App\Traits\GlossaryPageViewConditionTrait;
use App\Traits\LanguageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GlossaryAnalyticsController extends Controller
{
    use GenderTrait, GlossaryPageViewConditionTrait, LanguageTrait;

    public function index(Request $request): View
    {
        $languages = $this->getLanguages();
        $genders = $this->genders();
        $devices = Device::all(['id', 'name']);
        $browsers = Browser::all(['id', 'name']);
        $platforms = Platform::all(['id', 'name']);
        $glossarySubjects = GlossarySubject::all(['id', 'en']);
        $status = $request->status == 2 ? 'Created' : 'Views';

        $totalViews = $this->getTotalViews($request);
        $totalRegisteredUsersViews = $this->getTotalViews($request, 'no');

        $platformCounts = Platform::select(['id', 'name'])
            ->withCount([
                'glossaryPageViews' => function ($query) use ($request) {
                    return $this->filterPageViews($query, $request);
                },
            ])
            ->get();

        $browserCounts = Browser::select(['id', 'name'])
            ->withCount([
                'glossaryPageViews' => function ($query) use ($request) {
                    return $this->filterPageViews($query, $request);
                },
            ])
            ->get();

        $glossarySubjectCounts = GlossarySubject::select(['id', 'en'])
            ->withCount([
                'glossaryPageViews' => function ($query) use ($request) {
                    return $this->filterPageViews($query, $request);
                },
            ])
            ->get();

        $totalGuestViews = $this->getTotalViews($request, 'yes');
        $totalViewsBasedOnLanguage = $this->getTotalViewsBasedOnLanguage($request);

        return view('admin.analytics.glossary.index', compact('languages', 'genders', 'glossarySubjects', 'devices', 'platforms', 'browsers', 'totalViews', 'totalRegisteredUsersViews', 'totalGuestViews', 'platformCounts', 'browserCounts', 'glossarySubjectCounts', 'totalViewsBasedOnLanguage', 'status'));
    }

    private function getTotalViews($request, $isGuest = null): float
    {
        $query = GlossaryPageView::query();

        if ($isGuest) {
            if ($isGuest == 'yes') {
                $query->whereNull('user_id');
            } else {
                $query->whereNotNull('user_id');
            }
        }

        $query = $this->filterPageViews($query, $request);

        return $query->count();
    }

    private function getTotalViewsBasedOnLanguage($request): Collection
    {
        $totalResources = GlossaryPageView::where(function ($query) use ($request) {
            return $this->filterPageViews($query, $request);
        })
            ->groupBy('language')
            ->select('language', DB::raw('count(*) as view_count'))
            ->orderByDesc('view_count')
            ->get();

        return $totalResources->map(function ($item) {
            $item['language'] = LanguageEnum::tryFrom($item['language'])?->name ?? $item['language'];

            return $item;
        });
    }

    public function view(Request $request)
    {
        $languages = $this->getLanguages();
        $genders = $this->genders();
        $devices = Device::all(['id', 'name']);
        $browsers = Browser::all(['id', 'name']);
        $platforms = Platform::all(['id', 'name']);
        $glossarySubjects = GlossarySubject::all(['id', 'en']);

        $query = GlossaryPageView::query()->with(['glossarySubject:id,en', 'platform:id,name', 'device:id,name', 'browser:id,name', 'user:id', 'user.profile:id,user_id,first_name,last_name']);
        $query = $this->filterPageViews($query, $request);
        $views = $query->paginate()
            ->appends($request->except(['page']));

        return view('admin.analytics.glossary.get-views', compact(['views', 'glossarySubjects', 'languages', 'genders', 'devices', 'browsers', 'platforms']));
    }
}
