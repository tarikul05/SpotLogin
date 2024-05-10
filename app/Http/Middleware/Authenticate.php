<?php

namespace App\Http\Middleware;
use App\Providers\RouteServiceProvider;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if(!\Auth::user()){
            session()->flash('error', 'You must subscribe to access this page.');
            return RouteServiceProvider::LOGIN;
        } 
    }
}
