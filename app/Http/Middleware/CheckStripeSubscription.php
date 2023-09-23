<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckStripeSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is subscribed or if the trial end date is in the future
        if ($this->isSubscribed($user) || $this->isTrialValid($user)) {
            return $next($request);
        }

        // If the user doesn't have a valid subscription or trial, you can handle this as needed.
        // For example, you can redirect them to a subscription page or show an error message.
        return redirect()->route('subscription.upgradePlan')->with('error', 'You must subscribe to access this page.');
    }

    protected function isSubscribed($user)
    {
        // Use Laravel Cashier to check if the user is subscribed
        return $user->subscribed('default');
    }

    protected function isTrialValid($user)
    {
        // Check if the trial end date is in the future
        if ($user->trial_ends_at) {
            $trialEndDate = Carbon::parse($user->trial_ends_at);
            return $trialEndDate->isFuture();
        }

        return false;
    }

}
