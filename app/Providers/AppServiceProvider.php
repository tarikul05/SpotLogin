<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Language;
use App\Models\Country;
use DateTime;
use Exception;

class AppServiceProvider extends ServiceProvider
{
    protected $stripe;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

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

        view()->composer(
            ['layouts.auth', 'layouts.verify'],
            function ($view) {
                $language = Language::orderBy('sort_order')->get();
                $countries = Country::where([
                    ['is_active', 1],
                    ['deleted_at', null]
                ])->orderBy('code')->get();
                $view->with(compact('language', 'countries'));
            }
        );

        view()->composer(
            ['layouts.main'],
            function ($view) {
                $user = auth()->user();
                $is_subscribed = $user->subscribed('default');
                $today_date = new DateTime();
                if(!$is_subscribed){
                    $ends_at = $user->trial_ends_at;
                    $ends_at = new DateTime($ends_at);
                    $day_diff = $ends_at->diff($today_date)->format("%a");
                    $trial_ends_date = date('F j, Y, g:i a', strtotime($user->trial_ends_at));
                    $product_info = NULL;
                }else{
                    $trial_ends_date = null;
                    $day_diff = null;
                    $ends_at = new DateTime($user->trial_ends_at);
                    $plan = $user->subscriptions()->active()->first();
                    $product_info = $this->getPlanName($plan);
                }
                $view->with(compact('is_subscribed','trial_ends_date', 'day_diff','user', 'today_date', 'ends_at','product_info'));
            }
        );
    }

    public function getPlanName( $plan = null ){
        try{
            $product_name = $this->stripe->plans->retrieve($plan->stripe_price, []);
            return $this->getProductName($product_name);
        }catch(Exception $e){
            //
        }
    }

    public function getProductName( $product_name = null ){
        try{
            $product_info = $this->stripe->products->retrieve(
                $product_name->product,
                []
            );
            return $product_info;
        }catch(Exception $e){
            //
        }
    }

}
