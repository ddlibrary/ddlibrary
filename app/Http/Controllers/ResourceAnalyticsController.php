<?php

namespace App\Http\Controllers;

use App\Enums\LanguageEnum;
use App\Enums\TaxonomyVocabularyEnum;
use App\Models\DownloadCount;
use App\Models\Resource;
use App\Models\ResourceSubjectArea;
use App\Models\ResourceView;
use App\Models\TaxonomyTerm;
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

        $subjectAreas = TaxonomyTerm::selectRaw('taxonomy_term_data.*, COUNT(resource_subject_areas.tid) as resources_count')
            ->join('resource_subject_areas', 'taxonomy_term_data.id', '=', 'resource_subject_areas.tid')
            ->where('taxonomy_term_data.vid', TaxonomyVocabularyEnum::ResourceSubject)
            ->where('taxonomy_term_data.language', $request->language ?? 'en')
            ->having('resources_count', '>', 0)
            ->groupBy('taxonomy_term_data.id')
            ->orderByDesc('resources_count')
            ->get();

        $resourceTypes = TaxonomyTerm::selectRaw('taxonomy_term_data.*, COUNT(resource_learning_resource_types.tid) as resources_count')
            ->join('resource_learning_resource_types', 'taxonomy_term_data.id', '=', 'resource_learning_resource_types.tid')
            ->where('taxonomy_term_data.vid', TaxonomyVocabularyEnum::ResourceType)
            ->where('taxonomy_term_data.language', $request->language ?? 'en')
            ->having('resources_count', '>', 0)
            ->groupBy('taxonomy_term_data.id')
            ->orderByDesc('resources_count')
            ->get();

        $top10Authors = $this->getTop10AuthorsOrPublishers($request, 'resource_authors'); // Get top 10 authors
        $totalResources = $this->getTotalResourcesBasedOnLanguage($request); // Total Resources base on Language

        $top10FavoriteResources = $this->getTop10FavoriteResources($request); // Get top 10 favorite resources
        $top10Publishers = $this->getTop10AuthorsOrPublishers($request, 'resource_publishers'); // Get top 10 publishers
        $top10DownloadedResources = $this->getTop10DownloadedResources($request); // Get top 10 downloaded resources
        $top10DownloadedResourcesByFileSizes = $this->getTop10DownloadedResourcesByFileSize($request); // Get top 10 downloaded resources by file size
        $sumOfAllIndividualDownloadedFileSizes = $this->getSumOfAllIndividualDownloadedFileSizes(); // Sum of all individual downloaded file sizes

        return view('admin.analytics.resource-analytics.index', compact(['genders', 'languages', 'totalResources', 'sumOfAllIndividualDownloadedFileSizes', 'top10Authors', 'top10Publishers', 'top10DownloadedResources', 'top10DownloadedResourcesByFileSizes', 'top10FavoriteResources', 'subjectAreas', 'resourceTypes']));
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

    private function getSubjectArea($where = null, $language = 'en')
    {
        return TaxonomyTerm::select('taxonomy_term_data.id', 'language', 'name', 'tth.parent', 'tnid')
            ->leftJoin('taxonomy_term_hierarchy AS tth', 'tth.tid', '=', 'taxonomy_term_data.id')
            ->where('vid', TaxonomyVocabularyEnum::ResourceSubject)
            ->where(function ($query) use ($where) {
                if ($where) {
                    $query->where($where);
                }
            })
            ->where(function ($query) use ($language) {
                if ($language) {
                    $query->where('language', $language);
                }
            })
            ->orderBy('name')
            ->orderBy('weight', 'desc')
            ->get();
    }
    public function resourceSubjectArea(Request $request)
    {
        $englishResourceSubjectAreas = $this->getSubjectArea();
        $subjectCategories = $englishResourceSubjectAreas->where('parent', 0);

        $before2020Total = 0;
        $year2020Total = 0;
        $year2021Total = 0;
        $year2022Total = 0;
        $year2023Total = 0;
        $year2025Total = 0;
        $month1Total = 0;
        $month2Total = 0;
        $month3Total = 0;
        $month4Total = 0;
        $month5Total = 0;
        $month6Total = 0;
        $month7Total = 0;
        $month8Total = 0;
        $month9Total = 0;
        $month10Total = 0;
        $month11Total = 0;
        $month12Total = 0;

        $before2020ViewsTotal = 0;
        $year2020ViewsTotal = 0;
        $year2021ViewsTotal = 0;
        $year2022ViewsTotal = 0;
        $year2023ViewsTotal = 0;
        $year2025ViewsTotal = 0;
        $month1ViewsTotal = 0;
        $month2ViewsTotal = 0;
        $month3ViewsTotal = 0;
        $month4ViewsTotal = 0;
        $month5ViewsTotal = 0;
        $month6ViewsTotal = 0;
        $month7ViewsTotal = 0;
        $month8ViewsTotal = 0;
        $month9ViewsTotal = 0;
        $month10ViewsTotal = 0;
        $month11ViewsTotal = 0;
        $month12ViewsTotal = 0;
        foreach ($subjectCategories as $row) {
            $resourceSubjectAreas = $this->getSubjectArea(['tnid' => $row->tnid], null);

            $totalResources = 0;
            foreach ($resourceSubjectAreas as $subjectArea) {
                // dd($resourceSubjectAreas);
                $ids = $this->getSubjectArea(['parent' => $subjectArea->id], null)
                    ->pluck('id')
                    ->toArray();
                $ids[] = $subjectArea->id;

                // Debugging output
                $currentYear = date("Y");
                $total = $this->getResourceViewsCount($ids, null, null, false)->total_resources;
                $before2020Data = $this->getResourceViewsCount($ids, '2000-01-01', '2019-12-31');
                $year2020Data = $this->getResourceViewsCount($ids, '2020-01-01', '2020-12-31');
                $year2021Data = $this->getResourceViewsCount($ids, '2021-01-01', '2021-12-31');
                $year2022Data = $this->getResourceViewsCount($ids, '2022-01-01', '2022-12-31');
                $year2023Data = $this->getResourceViewsCount($ids, '2023-01-01', '2023-12-31');
                $year2025Data = $this->getResourceViewsCount($ids, '2025-01-01', '2025-12-31');
                $month1Data = $this->getResourceViewsCount($ids, "$currentYear-01-01", "$currentYear-01-31");
                $month2Data = $this->getResourceViewsCount($ids, "$currentYear-02-01", "$currentYear-02-31");
                $month3Data = $this->getResourceViewsCount($ids, "$currentYear-03-01", "$currentYear-03-31");
                $month4Data = $this->getResourceViewsCount($ids, "$currentYear-04-01", "$currentYear-04-31");
                $month5Data = $this->getResourceViewsCount($ids, "$currentYear-05-01", "$currentYear-05-31");
                $month6Data = $this->getResourceViewsCount($ids, "$currentYear-06-01", "$currentYear-06-31");
                $month7Data = $this->getResourceViewsCount($ids, "$currentYear-07-01", "$currentYear-07-31");
                $month8Data = $this->getResourceViewsCount($ids, "$currentYear-08-01", "$currentYear-08-31");
                $month9Data = $this->getResourceViewsCount($ids, "$currentYear-09-01", "$currentYear-09-31");
                $month10Data = $this->getResourceViewsCount($ids, "$currentYear-10-01", "$currentYear-10-31");
                $month11Data = $this->getResourceViewsCount($ids, "$currentYear-11-01", "$currentYear-11-31");
                $month12Data = $this->getResourceViewsCount($ids, "$currentYear-12-01", "$currentYear-12-31");

                $before2020 = $before2020Data->total_resources;
                $year2020 = $year2020Data->total_resources;
                $year2021 = $year2021Data->total_resources;
                $year2022 = $year2022Data->total_resources;
                $year2023 = $year2023Data->total_resources;
                $year2025 = $year2025Data->total_resources;
                $month1 = $month1Data->total_resources;
                $month2 = $month2Data->total_resources;
                $month3 = $month3Data->total_resources;
                $month4 = $month4Data->total_resources;
                $month5 = $month5Data->total_resources;
                $month6 = $month6Data->total_resources;
                $month7 = $month7Data->total_resources;
                $month8 = $month8Data->total_resources;
                $month9 = $month9Data->total_resources;
                $month10 = $month10Data->total_resources;
                $month11 = $month11Data->total_resources;
                $month12 = $month12Data->total_resources;

                $before2020Views = $before2020Data->total_views;
                $year2020Views = $year2020Data->total_views;
                $year2021Views = $year2021Data->total_views;
                $year2022Views = $year2022Data->total_views;
                $year2023Views = $year2023Data->total_views;
                $year2025Views = $year2025Data->total_views;
                $month1Views = $month1Data->total_views;
                $month2Views = $month2Data->total_views;
                $month3Views = $month3Data->total_views;
                $month4Views = $month4Data->total_views;
                $month5Views = $month5Data->total_views;
                $month6Views = $month6Data->total_views;
                $month7Views = $month7Data->total_views;
                $month8Views = $month8Data->total_views;
                $month9Views = $month9Data->total_views;
                $month10Views = $month10Data->total_views;
                $month11Views = $month11Data->total_views;
                $month12Views = $month12Data->total_views;

                $before2020Total += $before2020;
                $year2020Total += $year2020;
                $year2021Total += $year2021;
                $year2022Total += $year2022;
                $year2023Total += $year2023;
                $year2025Total += $year2025;
                $month1Total += $month1;
                $month2Total += $month2;
                $month3Total += $month3;
                $month4Total += $month4;
                $month5Total += $month5;
                $month6Total += $month6;
                $month7Total += $month7;
                $month8Total += $month8;
                $month9Total += $month9;
                $month10Total += $month10;
                $month11Total += $month11;
                $month12Total += $month12;

                $before2020ViewsTotal += $before2020Views;
                $year2020ViewsTotal += $year2020Views;
                $year2021ViewsTotal += $year2021Views;
                $year2022ViewsTotal += $year2022Views;
                $year2023ViewsTotal += $year2023Views;
                $year2025ViewsTotal += $year2025Views;
                $month1ViewsTotal += $month1Views;
                $month2ViewsTotal += $month2Views;
                $month3ViewsTotal += $month3Views;
                $month4ViewsTotal += $month4Views;
                $month5ViewsTotal += $month5Views;
                $month6ViewsTotal += $month6Views;
                $month7ViewsTotal += $month7Views;
                $month8ViewsTotal += $month8Views;
                $month9ViewsTotal += $month9Views;
                $month10ViewsTotal += $month10Views;
                $month11ViewsTotal += $month11Views;
                $month12ViewsTotal += $month12Views;

                $totalResources += $total;

                $subjectArea[$subjectArea->id] = [
                    'language' => $subjectArea->language,
                    'total' => $total,
                    'before2020' => $before2020,
                    'year2020' => $year2020,
                    'year2021' => $year2021,
                    'year2022' => $year2022,
                    'year2023' => $year2023,
                    'year2025' => $year2025,
                    'month1' => $month1,
                    'month2' => $month2,
                    'month3' => $month3,
                    'month4' => $month4,
                    'month5' => $month5,
                    'month6' => $month6,
                    'month7' => $month7,
                    'month8' => $month8,
                    'month9' => $month9,
                    'month10' => $month10,
                    'month11' => $month11,
                    'month12' => $month12,

                    'before2020Views' => $before2020Views,
                    'year2020Views' => $year2020Views,
                    'year2021Views' => $year2021Views,
                    'year2022Views' => $year2022Views,
                    'year2023Views' => $year2023Views,
                    'year2025Views' => $year2025Views,
                    'month1Views' => $month1Views,
                    'month2Views' => $month2Views,
                    'month3Views' => $month3Views,
                    'month4Views' => $month4Views,
                    'month5Views' => $month5Views,
                    'month6Views' => $month6Views,
                    'month7Views' => $month7Views,
                    'month8Views' => $month8Views,
                    'month9Views' => $month9Views,
                    'month10Views' => $month10Views,
                    'month11Views' => $month11Views,
                    'month12Views' => $month12Views,
                ];
            }

            $subjectCategories[$row->id] = [
                'resource_subject_areas' => $resourceSubjectAreas,
                'total_resources' => $totalResources,
                'name' => $row->name,
            ];
        }
        return view(
            'admin.analytics.resource-analytics.resource-subject-area',
            compact(
                'subjectCategories',
                'englishResourceSubjectAreas',
                'before2020Total',
                'year2020Total',
                'year2021Total',
                'year2022Total',
                'year2023Total',
                'year2025Total',
                'month1Total',
                'month2Total',
                'month3Total',
                'month4Total',
                'month5Total',
                'month6Total',
                'month7Total',
                'month8Total',
                'month9Total',
                'month10Total',
                'month11Total',
                'month12Total',
                'before2020ViewsTotal',
                'year2020ViewsTotal',
                'year2021ViewsTotal',
                'year2022ViewsTotal',
                'year2023ViewsTotal',
                'year2025ViewsTotal',
                'month1ViewsTotal',
                'month2ViewsTotal',
                'month3ViewsTotal',
                'month4ViewsTotal',
                'month5ViewsTotal',
                'month6ViewsTotal',
                'month7ViewsTotal',
                'month8ViewsTotal',
                'month9ViewsTotal',
                'month10ViewsTotal',
                'month11ViewsTotal',
                'month12ViewsTotal',
            ),
        );
    }

    private function getResourcesCount($tids, $startDate = null, $endDate = null)
    {
        return ResourceSubjectArea::whereHas('resource', function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        })
            ->whereIn('tid', $tids)
            ->count();
    }

    private function getResourceViewsCount($tids, $startDate = null, $endDate = null, $bothQueries = true)
    {
        $resourceIds = ResourceSubjectArea::whereIn('tid', $tids)->pluck('resource_id');

        $totalViews = 0;
        $totalResources = 0;
        if (count($resourceIds)) {
            if ($bothQueries) {
                $totalViews = ResourceView::whereIn('resource_id', $resourceIds)
                    ->where(function ($query) use ($startDate, $endDate) {
                        if ($startDate && $endDate) {
                            $query->where('created_at', '>=', "$startDate 00:00:00");
                            $query->where('created_at', '<=', "$endDate 23:59:59");
                        } else {
                            $query->where('created_at', '>=', "2000-01-01 00:00:00");
                            $query->where('created_at', '<=', date('Y')."-12-31 23:59:59");
                        }
                    })
                    ->count();
            }

            $totalResources = Resource::whereIn('id', $resourceIds)
                ->where(function ($query) use ($startDate, $endDate) {
                    if ($startDate && $endDate) {
                        $query->where('created_at', '>=', "$startDate 00:00:00");
                        $query->where('created_at', '<=', "$endDate 23:59:59");
                    }
                })
                ->count();
        }

        return (object) [
            'total_views' => $totalViews,
            'total_resources' => $totalResources,
        ];
    }
}
