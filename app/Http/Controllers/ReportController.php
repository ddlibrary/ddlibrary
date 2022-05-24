<?php

namespace App\Http\Controllers;

use Analytics;
use App\Resource;
use App\ResourceLevel;
use App\ResourceSubjectArea;
use App\TaxonomyTerm;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    private static function subjectSort($a, $b): int
    {
        return $a['count'] <=> $b['count'];
    }

    /**
     * @param $subjects
     * @return array
     */
    private static function getSubjects_list($subjects, $language = false): array
    {
        $subjects_list = [];
        foreach ($subjects as $subject) {
            $subjects_list[$subject->id]['id'] = $subject->id;
            $subjects_list[$subject->id]['name'] = $subject->name;
            if ($language) {
                $supported_locales = \LaravelLocalization::getSupportedLocales();
                $subjects_list[$subject->id]['language'] = $supported_locales[$subject->language]['native'];
            }
        }

        foreach ($subjects_list as $subject_id => $name) {
            $subject_resources = ResourceSubjectArea::where('tid', $subject_id)->get();
            $subjects_list[$subject_id]['count'] = $subject_resources->count();
        }
        return $subjects_list;
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

    public function resourceReport(): Factory|View|Application
    {
        $supported_locales = \LaravelLocalization::getSupportedLocales();
        return view('admin.reports.resource_reports', compact('supported_locales'));
    }

    public function resourceSubjectReport(Request $request): BinaryFileResponse
    {
        $language = $request->input('lang', 'en');

        $subjects = TaxonomyTerm::where([
            ['vid', '=', 8],  // 8 = Subject
            ['language', '=', $language]
        ])->get();

        $subjects_list = [];
        foreach ($subjects as $subject) {
            $subjects_list[$subject->id] = $subject->name;
        }

        $levels = TaxonomyTerm::where([
            ['vid', '=', 13],  // 13 = Grade level
            ['language', '=', $language]
        ])->get();

        $levels_list = [];
        foreach ($levels as $level) {
            $levels_list[$level->id]['name'] = $level->name;
            $levels_list[$level->id]['count'] = 0;
        }

        $headers = array(
            'Content-Type' => 'text/csv',
            "Content-Disposition" => "attachment; filename=theincircle_csv.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $filename = tempnam('/tmp', 'csv_');
        $file = fopen($filename, 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($file, [
            "Subject name",
            "Resource Level",
            "# of resources",
        ]);

        foreach ($subjects_list as $subject_id => $name) {
            $subject_resources = ResourceSubjectArea::where('tid', $subject_id)->get();
            fputcsv($file, [
                $name,
                "",
                $subject_resources->count(),
            ]);

            foreach ($subject_resources as $subject_resource) {
                $levels = ResourceLevel::where('resource_id', '=', $subject_resource->resource_id)->get();
                foreach ($levels as $level) {
                    $levels_list[$level->tid]['count'] += 1;
                }
            }
            foreach ($levels_list as $level_id => $level) {
                if ($level['count'] != 0 && $level['name'] != "") {
                    fputcsv($file, [
                        "",
                        $level['name'],
                        $level['count'],
                    ]);
                }
                $levels_list[$level_id]['count'] = 0;
            }
        }

        fclose($file);

        return response()->download($filename, "resource_subject_report.csv", $headers);
    }

    public function resourceLanguageReport(): BinaryFileResponse
    {

        $all_languages = Resource::select('language')->distinct()->get();

        $languages_list = [];
        foreach ($all_languages as $language) {
            $languages_list[$language->language]['language_resources_count'] = Resource::where(
                'language', '=', $language->language
            )->get()->count();
            $levels = TaxonomyTerm::where([
                ['vid', '=', 13],  // 13 = Grade level
                ['language', '=', $language->language]
            ])->get();
            foreach ($levels as $level) {
                $levels_count = ResourceLevel::where('tid', '=', $level->id)->get()->count();
                $languages_list[$language->language]['level_resources_count'][$level->name] = $levels_count;
            }
        }

        $headers = array(
            'Content-Type' => 'text/csv',
            "Content-Disposition" => "attachment; filename=theincircle_csv.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $filename = tempnam('/tmp', 'csv_');
        $file = fopen($filename, 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($file, [
            "Language",
            "Resource Level",
            "# of resources",
        ]);

        $supported_locales = \LaravelLocalization::getSupportedLocales();

        foreach ($languages_list as $language => $counts) {
            fputcsv($file, [
                $supported_locales[$language]['native'],
                "",
                $counts['language_resources_count'],
            ]);
            foreach ($counts as $count) {
                if (is_array($count)) {
                    foreach ($count as $level_name => $level_resources_count) {
                        fputcsv($file, [
                            "",
                            $level_name,
                            $level_resources_count,
                        ]);
                    }
                }
            }
        }

        fclose($file);

        return response()->download($filename, "resource_language_report.csv", $headers);
    }

    public function resourcePriorities(): Factory|View|Application
    {
        $language = app()->getLocale();

        $subjects = TaxonomyTerm::where([
            ['vid', '=', 8],  // 8 = Subject
            ['language', '=', $language],
            ['excluded', '=', false]
        ])->get();

        $subjects_list = self::getSubjects_list($subjects);

        usort($subjects_list, 'self::subjectSort');

        return view('reports.priorities', compact('subjects_list'));
    }

    public function resourcePrioritiesExclusion(): Factory|View|Application
    {
        $subjects = TaxonomyTerm::where([
            ['vid', '=', 8],  // 8 = Subject
            ['excluded', '=', true]
        ])->get();

        $subjects_list = self::getSubjects_list($subjects, true);

        return view('reports.exclusion', compact('subjects_list'));
    }

    public function resourcePrioritiesExclusionModify($id): JsonResponse
    {
        $subject = TaxonomyTerm::where('id', '=', $id)->first();

        if (!$subject) {
            return response()->json(array('msg'=> 'error'), 400);
        }

        $subject->excluded ? $subject->excluded = false : $subject->excluded = true;
        $subject->save();

        return response()->json(array('msg'=> 'success'));
    }
}
