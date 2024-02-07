<?php

namespace App\Providers;

use App\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (Schema::hasTable('menus')) {
            View::share('menu', Menu::orderBy('weight')->get());
        }
    }
}
