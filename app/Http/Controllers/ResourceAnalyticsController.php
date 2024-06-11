<?php

namespace App\Http\Controllers;

use App\Enums\LanguageEnum;
use App\Models\DownloadCount;
use App\Models\Resource;
use App\Traits\GenderTrait;
use App\Traits\LanguageTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResourceAnalyticsController extends Controller
{
    use LanguageTrait, GenderTrait;

    public function index(Request $request): View
    {
        $genders = $this->genders();
        $languages = $this->getLanguages();

        $top10Authors = $this->getTop10AuthorsOrPublishers($request, 'resource_authors'); // Get top 10 authors
        $totalResources = $this->getTotalResourcesBasedOnLanguage($request); // Total Resources base on Language

        $top10FavoriteResources = $this->getTop10FavoriteResources($request); // Get top 10 favorite resources
        $top10Publishers = $this->getTop10AuthorsOrPublishers($request, 'resource_publishers'); // Get top 10 publishers
        $top10DownloadedResources = $this->getTop10DownloadedResources($request); // Get top 10 downloaded resources
        $top10DownloadedResourcesByFileSizes = $this->getTop10DownloadedResourcesByFileSize($request); // Get top 10 downloaded resources by file size
        $sumOfAllIndividualDownloadedFileSizes = $this->getSumOfAllIndividualDownloadedFileSizes(); // Sum of all individual downloaded file sizes

        return view('admin.analytics.resource-analytics.index', compact(['genders', 'languages', 'totalResources', 'sumOfAllIndividualDownloadedFileSizes', 'top10Authors', 'top10Publishers', 'top10DownloadedResources', 'top10DownloadedResourcesByFileSizes', 'top10FavoriteResources']));
    }

    private function getSumOfAllIndividualDownloadedFileSizes(): float
    {
        return DownloadCount::leftJoin('resource_attachments', 'download_counts.file_id', '=', 'resource_attachments.id')->select(DB::raw('COALESCE(SUM(resource_attachments.file_size), 0) as total_file_size'))->value('total_file_size');
    }

    private function getTotalResourcesBasedOnLanguage($request): Collection
    {
        $totalResources = Resource::where(function ($query) use ($request) {
            // Date
            if ($request->date_from && $request->date_to) {
                $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
            }

            // Language
            if ($request->language) {
                $query->where('language', $request->language);
            }

            // Gender
            if ($request->gender) {
                $query->whereHas('user.profile', function ($query) use ($request) {
                    $query->where('gender', $request->gender);
                });
            }
        })
            ->groupBy('language')
            ->select('language', DB::raw('count(*) as count'))
            ->orderBy('count', 'desc')
            ->get();

        return $totalResources->map(function ($item) {
            $item['language'] = LanguageEnum::tryFrom($item['language'])?->name ?? $item['language'];
            return $item;
        });
    }

    private function getTop10AuthorsOrPublishers($request, $table): Collection
    {
        return Resource::select('taxonomy_term_data.id AS id', 'taxonomy_term_data.name AS name', DB::raw('COUNT(resources.id)  resource_count'))
            ->join("$table", 'resources.id', '=', "$table.resource_id")
            ->join('taxonomy_term_data', "$table.tid", '=', 'taxonomy_term_data.id')
            ->where(function ($query) use ($request) {
                // Date
                if ($request->date_from && $request->date_to) {
                    $query->whereBetween('resources.created_at', [$request->date_from, $request->date_to]);
                }

                // Language
                if ($request->language) {
                    $query->where('resources.language', $request->language);
                }
            })
            ->groupBy('taxonomy_term_data.id')
            ->orderByDesc('resource_count')
            ->limit(10)
            ->get();
    }

    private function getTop10DownloadedResourcesByFileSize($request): Collection
    {
        return Resource::select('resources.id', 'resources.title', DB::raw('MAX(resource_attachments.file_size) AS file_size'))
            ->join('download_counts', 'resources.id', '=', 'download_counts.resource_id')
            ->join('resource_attachments', 'download_counts.file_id', '=', 'resource_attachments.id')
            ->where(function ($query) use ($request) {
                // Language
                if ($request->language) {
                    $query->where('language', $request->language);
                }

                // Date
                if ($request->date_from && $request->date_to) {
                    $query->whereBetween('download_counts.created_at', [$request->date_from, $request->date_to]);
                }

                // Gender
                if ($request->gender) {
                    $query->whereHas('user.profile', function ($query) use ($request) {
                        $query->where('gender', $request->gender);
                    });
                }
            })
            ->groupBy('resources.id')
            ->limit(10)
            ->get();
    }

    private function getTop10DownloadedResources($request): Collection
    {
        $query = Resource::query()
            ->select(['id', 'title', 'language'])
            ->whereHas('downloads', function ($query) use ($request) {
                // Date
                if ($request->date_from && $request->date_to) {
                    $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
                }
            })
            ->withCount('downloads');

        // Language
        if ($request->language) {
            $query->where('language', $request->language);
        }

        return $query->orderBy('downloads_count', 'desc')->take(10)->get();
    }

    private function getTop10FavoriteResources($request): Collection
    {
        $query = Resource::query()
            ->select(['id', 'title', 'language'])
            ->withCount('resourceFavorites');

        if ($request->date_from && $request->date_to) {
            $query->whereHas('resourceFavorites', function ($subQuery) use ($request) {
                $subQuery->whereBetween('created_at', [$request->date_from, $request->date_to]);
            });
        }

        if ($request->language) {
            $query->where('language', $request->language);
        }

        return $query->orderByDesc('resource_favorites_count')->take(10)->get();
    }
}
