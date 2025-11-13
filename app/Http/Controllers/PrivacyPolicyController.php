<?php

namespace App\Http\Controllers;

use App\Traits\SitewidePageViewTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrivacyPolicyController extends Controller
{
    use SitewidePageViewTrait;

    public function index(Request $request): View
    {
        $this->pageView($request, 'Privacy Policy');
        DDLClearSession();

        if(config('app.locale') == 'en'){
            return view('privacy-policy.english');
        }elseif(config('app.locale') == 'ps'){
            return view('privacy-policy.pashto');
        }

        return view('privacy-policy.farsi');
    }

    public function mobilePrivacyPolicy(Request $request): View
    {
        $this->pageView($request, 'Privacy Policy - Mobile Application');
        DDLClearSession();

        return view('privacy-policy.mobile');
    }
}
