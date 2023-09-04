<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Stripe\Stripe;
use Stripe\Webhook;


/**
 * Handle the webhook request.
 *
 * @param Request $request The request object.
 * @throws \UnexpectedValueException If the event cannot be constructed.
 * @throws \Stripe\Exception\SignatureVerificationException If the signature verification fails.
 * @return \Illuminate\Http\JsonResponse The response indicating the webhook was received.
 */
class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $endpointSecret = 'whsec_ueBB01Z6TTr7uNZ1i5YNBwiE9lR26Ino';

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $event = null;

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            abort(400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            abort(400);
        }

        // Traiter les événements du webhook
        if ($event->type == 'customer.subscription.created' || $event->type == 'customer.subscription.updated') {
            $subscription = $event->data->object;

            // Rechercher l'utilisateur par stripe_id
            $user = User::where('stripe_id', $subscription->customer)->first();

            if ($user) {
                if (in_array($subscription->status, ['active', 'trialing'])) {
                    // Si l'abonnement est actif ou en période d'essai, retirez le rôle en lecture seule
                    $user->removeRole('single_coach_read_only');
                } else {
                    // Sinon, assignez le rôle en lecture seule
                    $user->assignRole('single_coach_read_only');
                }

                $user->subscriptions()->where('stripe_id', $subscription->id)->update([
                    'stripe_status' => $subscription->status,
                ]);

                $user->last_stripe_check = Carbon::now();
                $user->save();
            }
        } elseif ($event->type == 'customer.subscription.deleted') {
            $subscription = $event->data->object;

            // Rechercher l'utilisateur par stripe_id
            $user = User::where('stripe_id', $subscription->customer)->first();
            $trialEndsAt = Carbon::createFromTimestamp($timestamp);
            if ($user) {
                // Assignez le rôle en lecture seule lorsque l'abonnement est supprimé
                $user->assignRole('single_coach_read_only');

                $user->subscriptions()->where('stripe_id', $subscription->id)->update([
                    'stripe_status' => $subscription->status,
                    'trial_ends_at' => $trialEndsAt
                ]);

                $user->last_stripe_check = Carbon::now();
                $user->save();
            }
        } elseif ($event->type == 'customer.subscription.trial_will_end') {
            $subscription = $event->data->object;

            // Rechercher l'utilisateur par stripe_id
            $user = User::where('stripe_id', $subscription->customer)->first();
            $trialEndsAt = Carbon::createFromTimestamp($timestamp);

            if ($user) {
                // Assignez le rôle en lecture seule lorsque l'abonnement est supprimé
                $user->assignRole('single_coach_read_only');

                $user->subscriptions()->where('stripe_id', $subscription->id)->update([
                    'stripe_status' => $subscription->status,
                    'trial_ends_at' => $trialEndsAt
                ]);

                $user->last_stripe_check = Carbon::now();
                $user->save();
            }
        }

        return response()->json(['received' => true]);
    }

}
