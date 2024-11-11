<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SchoolTeacher;
use App\Models\User;

class CheckStripeAccessSubscription
{
    public function handle($request, Closure $next)
    {
        $userConnected = Auth::user();
        $schoolId = $userConnected->school_id;
       

        // 1. Récupérer le SchoolTeacher avec le school_id correspondant et le role_type "school_admin"
        $schoolTeacher = SchoolTeacher::where('school_id', $schoolId)
        ->where('role_type', 'school_admin')
        ->first();

        if (!$schoolTeacher) {
        // Aucun administrateur pour cette école trouvé, donc pas d'abonnement premium
        return false;
        }

        // 2. Récupérer le teacher_id
        $teacherId = $schoolTeacher->teacher_id;

        // 3. Utiliser le teacher_id pour trouver le User correspondant
        $user = User::where('person_type', 'App\Models\Teacher')
        ->where('person_id', $teacherId)
        ->first();



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
       // Check the custom subscription status field
       $subscription = $user->subscriptions()->where('name', 'default')->first();
       if ($subscription) {
           return $subscription->stripe_status === 'active' || $subscription->stripe_status === 'trialing' || $subscription->stripe_status === 'succeeded';
       }
       return false;
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
