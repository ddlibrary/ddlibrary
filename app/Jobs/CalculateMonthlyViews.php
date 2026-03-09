<?php

namespace App\Jobs;

use App\Models\SitewidePageView;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CalculateMonthlyViews implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $monthlyViews = SitewidePageView::where('created_at', '>', \Carbon\Carbon::now()->subDays(30))
            ->where(function ($views) {
                $views->where(function ($query) {
                    $query->where('is_bot', false);
                })
                    ->where(function ($query) {
                        $query->where('browser', '!=', 'Mozilla')
                            ->where('platform_id', '!=', 0);
                    });
            })
            ->count();
        Cache::put('monthlyViews', $monthlyViews);
        Cache::put('monthlyViewsTimestamp', date('Y-m-d'));
    }
}
