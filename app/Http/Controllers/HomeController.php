<?php

namespace App\Http\Controllers;
use App\News;
use App\Resource;
use App\Survey;
use App\SurveyQuestion;
use App\SurveyQuestionOption;
use BladeView;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @return Application|BladeView|Factory|false
     */
    public function index(Request $request)
    {
        //setting the search session empty
        DDLClearSession();
        
        $resources = new Resource();

        //latest news for the homepage
        $latestNews         = News::where('language',Config::get('app.locale'))->where('status', 1)->orderBy('id','desc')->take(4)->get();
        $subjectAreas       = $resources->subjectIconsAndTotal();
        $featured           = $resources->featuredCollections();
        $latestResources    = Resource::published()->where('language',Config::get('app.locale'))->orderBy('id','desc')->take(4)->get();
        \Carbon\Carbon::setLocale(app()->getLocale());
        $surveys = Survey::find(1);
        $surveyQuestions = SurveyQuestion::where('survey_id', 1)->first();
        $surveyQuestionOptions = SurveyQuestionOption::where('question_id', 1)->get();
        return view('home', compact(
            'latestNews',
            'subjectAreas',
            'featured',
            'latestResources',
            'surveys',
            'surveyQuestions',
            'surveyQuestionOptions'
        ));
    }
}
