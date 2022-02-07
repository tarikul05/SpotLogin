<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Language;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'layouts.auth', 
            function ($view) {
                $view->with('language', Language::orderBy('sort_order')->get());
            }
        );
    }
}
