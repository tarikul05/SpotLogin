<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use DateTime;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        // Implicitly grant "superadmin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $this->check_read_only($user, $ability);
            // return $user->hasRole('superadmin') ? true : null;
        });
    }

    public function check_read_only($user,  $ability){
        // return $user->isSchoolAdmin();
        $today_date = new DateTime();
        $today_time = $today_date->getTimestamp();
        if( !empty($user->trial_ends_at) && !empty($user->stripe_id)){
            $expired_date = strtotime($user->trial_ends_at);
            if($today_time >= $expired_date){
                return $user->hasRole('read_only') ? true : null;
            }
        }
        return $user->hasRole('superadmin') ? true : null;
    }
}
