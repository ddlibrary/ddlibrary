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
            return view('policies.privacy_en_web_current');
        }elseif(config('app.locale') == 'ps'){
            return view('policies.privacy_ps_web_current');
        }

        return view('policies.privacy_fa_web_current');
    }

    public function mobilePrivacyPolicy(Request $request): View
    {
        $this->pageView($request, 'Privacy Policy - Mobile Application');
        DDLClearSession();

        return view('policies.mob_current');
    }
}
