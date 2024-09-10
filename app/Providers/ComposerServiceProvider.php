<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            DB::connection()->getPDO();
            View::share('menu', Menu::query()->orderBy('weight')->get());
        } catch (\Exception $e) {
        }
    }
}
