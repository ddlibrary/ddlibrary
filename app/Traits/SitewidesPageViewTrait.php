<?php

namespace App\Traits;

use App\Models\Browser;
use App\Models\Device;
use App\Models\PageType;
use App\Models\Platform;
use App\Models\SitewidesPageView;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait SitewidesPageViewTrait
{
    public function visit(Request $request, $title = null): void
    {
     
        $languageCode = $this->getLanguageCode();

        if (count(explode("/$languageCode", $request->url())) == 2) {
            $agent = new Agent();
            $pageType = $this->getPageType($request->url());

            $device = Device::firstOrCreate(['name' => $agent->device()]);
            $pageTypeModel = PageType::firstOrCreate(['name' => $pageType]);
            $platform = Platform::firstOrCreate(['name' => $agent->platform()]);
            $browser = Browser::firstOrCreate(['name' => $agent->browser    ()]);

            SitewidesPageView::insert([
                'title' => $title,
                'page_url' => $request->url(),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'browser_id' => $browser->id,
                'browser' => $browser->name. ' '. $agent->version($browser->name),
                'is_bot' => $agent->isBot(),
                'language' => $languageCode,
                'page_type_id' => $pageTypeModel->id,
                'device_id' => $device->id,
                'platform_id' => $platform->id,
                'user_id' => $request->user()?->id,
                'gender' => $request->user()?->profile?->gender,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    protected function getPageType(string $url): string
    {
        $patterns = [
            '/resources' => 'Resource List',
            '/resource' => 'Resource Details',
            '/contact-us' => 'Contact Us',
            '/impact' => 'Impact',
            '/news' => 'News',
        ];

        foreach ($patterns as $pattern => $type) {
            if (preg_match("~{$pattern}~", $url)) {
                return $type;
            }
        }

        return 'page';
    }

    protected function getLanguageCode(): ?string
    {
        return LaravelLocalization::getCurrentLocale();
    }
}
