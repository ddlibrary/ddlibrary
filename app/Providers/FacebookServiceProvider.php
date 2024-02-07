<?php

namespace App\Providers;

use Facebook\Facebook;
use Illuminate\Support\ServiceProvider;

class FacebookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Facebook::class, function ($app) {
            return new Facebook(config('facebook.config'));
        });
    }
}
