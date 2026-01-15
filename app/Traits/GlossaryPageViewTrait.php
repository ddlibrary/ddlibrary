<?php

namespace App\Traits;

use App\Enums\GlossaryPageViewStatusEnum;
use App\Models\Browser;
use App\Models\Device;
use App\Models\GlossaryPageView;
use App\Models\GlossarySubject;
use App\Models\Platform;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait GlossaryPageViewTrait
{
    public function pageView(Request $request, $status, $title = null): void
    {
        $languageCode = $this->getLanguageCode();

        if (count(explode("/$languageCode", $request->url())) == 2) {
            $agent = new Agent;

            $device = Device::firstOrCreate(['name' => $agent->device()]);
            $platform = Platform::firstOrCreate(['name' => $agent->platform()]);
            $browser = Browser::firstOrCreate(['name' => $agent->browser()]);
            $title = $request->subject ? ($status == GlossaryPageViewStatusEnum::Create ? $request->english : GlossarySubject::whereId($request->subject)->value('en')) : $title;

            GlossaryPageView::insert([
                'title' => $title,
                'user_agent' => $request->userAgent(),
                'browser_id' => $browser->id,
                'browser' => $browser->name.' '.$agent->version($browser->name),
                'is_bot' => $agent->isBot(),
                'language' => $languageCode,
                'device_id' => $device->id,
                'platform_id' => $platform->id,
                'user_id' => $request->user()?->id,
                'glossary_subject_id' => $request->subject,
                'gender' => $request->user()?->profile?->gender,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    protected function getLanguageCode(): ?string
    {
        return LaravelLocalization::getCurrentLocale();
    }
}
