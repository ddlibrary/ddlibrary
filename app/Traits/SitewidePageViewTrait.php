<?php

namespace App\Traits;

use App\Models\Browser;
use App\Models\Device;
use App\Models\Platform;
use App\Models\SitewidePageView;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait SitewidePageViewTrait
{
    public function pageView(Request $request, $title = null): void
    {
        /*    
        TODO: this is not sustainable if we don't cleanup the log every month
        $languageCode = $this->getLanguageCode();

        if (count(explode("/$languageCode", $request->url())) == 2) {
            $agent = new Agent();

            $device = Device::firstOrCreate(['name' => $agent->device()]);
            $platform = Platform::firstOrCreate(['name' => $agent->platform()]);
            $browser = Browser::firstOrCreate(['name' => $agent->browser()]);

            SitewidePageView::insert([
                'title' => $title,
                'page_url' => $request->url(),
                'user_agent' => $request->userAgent(),
                'browser_id' => $browser->id,
                'browser' => $browser->name. ' '. $agent->version($browser->name),
                'is_bot' => $agent->isBot(),
                'language' => $languageCode,
                'device_id' => $device->id,
                'platform_id' => $platform->id,
                'user_id' => $request->user()?->id,
                'gender' => $request->user()?->profile?->gender,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        */ 
    }

    protected function getLanguageCode(): ?string
    {
        return LaravelLocalization::getCurrentLocale();
    }
}
