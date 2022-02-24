<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Language;
use App\Models\Country;

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
            ['layouts.auth', 'layouts.raw'], 
            function ($view) {
                $language = Language::orderBy('sort_order')->get();
                $countries = Country::where([
                    ['is_active', 1],
                    ['deleted_at', null]
                ])->orderBy('code')->get();
                $view->with(compact('language', 'countries'));
            }
        );
    }
}
