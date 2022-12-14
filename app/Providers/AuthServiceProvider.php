<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use DateTime;
use Spatie\Permission\Models\Role;

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
        // dd($user->isSchoolAdmin(), $user->isReadOnly());
        // dd($user->getPermissionsViaRoles()->toArray());
        // dd($com_role);
        $today_date = new DateTime();
        $today_time = $today_date->getTimestamp();
        if( !empty($user->trial_ends_at) && !empty($user->stripe_id)){
            $expired_date = strtotime($user->trial_ends_at);
            if($today_time >= $expired_date){
                $roles_all = $user->getPermissionsViaRoles()->toArray();
                $role = Role::where('name','read_only')->first();
                $role_read_only = $role->getAllPermissions()->toArray();
                $com_role = [];
                foreach($roles_all as $key => $value) {
                    $search_value = $value['id'];
                    $search_key   = 'id';
                    $rol = $this->search_array_val($search_key, $search_value, $role_read_only);
                    if($rol){
                        array_push($com_role, $rol);
                    }
                }
                $role_un = $this->remove_duplicate_role($com_role);
                $has_value = array_search($ability, array_column($role_un, 'name'));
                if($has_value){
                    return $user->hasRole('read_only') ? true : null;
                }else{
                    return $user->hasRole('superadmin') ? true : null;
                }
            }
        }
        return $user->hasRole('superadmin') ? true : null;
    }

    public function search_array_val($sKey, $id, $array) {
        foreach ($array as $key => $val) {
            if ($val[$sKey] == $id) {
                return $val;
            }
        }
        return false;
    }

    public function remove_duplicate_role($input)
    {
        $serialized = array_map('serialize', $input);
        $unique = array_unique($serialized);
        return array_intersect_key($input, $unique);
    }
}
