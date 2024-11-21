<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Support\Carbon;
use Stripe\Stripe;
use Stripe\Webhook;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailTemplate;
use App\Models\School;
use App\Mail\PaymentConfirmation;
use App\Mail\SubscriptionConfirmation;
use App\Mail\SubscriptionUpdate;

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
        Stripe::setApiKey(config('services.stripe.secret'));

        $endpointSecret = env('STRIPE_ENDPOINT_SECRET');

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
            $trialEndsAt = Carbon::createFromTimestamp($subscription->trial_end);
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
                    'trial_ends_at' => $trialEndsAt
                ]);

                $user->last_stripe_check = Carbon::now();
                $user->save();

                if ($event->type == 'customer.subscription.created') {

                    $customer = \Stripe\Customer::retrieve($subscription->customer);
                    $defaultPaymentMethodId = $customer->invoice_settings->default_payment_method;


                        if ($defaultPaymentMethodId) {
                            $paymentMethod = \Stripe\PaymentMethod::retrieve($defaultPaymentMethodId);

                            // Récupérer les 4 derniers chiffres de la carte
                            $brand = $paymentMethod->card->brand;
                            $last4 = $paymentMethod->card->last4;

                        } else {
                            $brand = 'Unknown';
                            $last4 = 'Unknown';
                        }
              

                    $school = School::find($subscription['metadata']['schoolID']);
                    $schoolName = $school->school_name;

                    $subscriptionDetails = [
                        'plan_name' => $subscription['items']['data'][0]['price']['nickname'] ?? 'Unknown Plan',
                        'amount' => $subscription['items']['data'][0]['price']['unit_amount'] / 100,
                        'currency' => strtoupper($subscription['currency']),
                        'billing_period' => $subscription['items']['data'][0]['price']['recurring']['interval'],
                        'start_date' => date('d-m-Y H:i:s', $subscription['current_period_start']),
                        'next_billing_date' => date('d-m-Y H:i:s', $subscription['current_period_end']),
                        'payment_method' => $brand . ' **** ' . $last4,
                        'customer_email' => $subscription['metadata']['email'] ?? 'Unknown Email',
                        'customer_name' => $subscription['metadata']['name'] ?? 'Unknown Name',
                        'school_name' => $schoolName ?? 'Unknown School',
                        'number_of_coaches' => $subscription['metadata']['number_of_coaches'] ?? 1,
                        'status' => $subscription['status'],
                        'trial_end' => $subscription['trial_end'] ?? null,
                    ];
                    
                    // Send confirmation email
                    Mail::to($user->email)->send(new SubscriptionConfirmation($subscriptionDetails));
                }

                if ($event->type == 'customer.subscription.updated') {

                    $school = School::find($subscription['metadata']['schoolID']);
                    $schoolName = $school->school_name;

                    $subscriptionDetails = [
                        'plan_name' => $subscription['items']['data'][0]['price']['nickname'] ?? 'Unknown Plan',
                        'amount' => $subscription['items']['data'][0]['price']['unit_amount'] / 100,
                        'currency' => strtoupper($subscription['currency']),
                        'billing_period' => $subscription['items']['data'][0]['price']['recurring']['interval'],
                        'start_date' => date('d-m-Y H:i:s', $subscription['current_period_start']),
                        'next_billing_date' => date('d-m-Y H:i:s', $subscription['current_period_end']),
                        'payment_method' => $subscription['metadata']['note'] ?? 'N/A', // Remplacer par le champ exact si besoin
                        'customer_email' => $subscription['metadata']['email'] ?? 'Unknown Email',
                        'customer_name' => $subscription['metadata']['name'] ?? 'Unknown Name',
                        'school_name' => $schoolName ?? 'Unknown School',
                        'number_of_coaches' => $subscription['metadata']['number_of_coaches'] ?? 1,
                        'status' => $subscription['status'],
                        'cancel_at_period_end' => $subscription['cancel_at_period_end']
                    ];
                    
                    // Send update subscription email
                    if($subscription['cancel_at_period_end']) {
                        Mail::to($user->email)->send(new SubscriptionUpdate($subscriptionDetails));
                    }
                }

            }
        } elseif ($event->type == 'customer.subscription.deleted') {
            $subscription = $event->data->object;

            // Rechercher l'utilisateur par stripe_id
            $user = User::where('stripe_id', $subscription->customer)->first();
            $trialEndsAt = Carbon::createFromTimestamp($subscription->trial_end);
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
            $trialEndsAt = Carbon::createFromTimestamp($subscription->trial_end);

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


        elseif ($event->type == 'payment_intent.succeeded') {

            //Validate invoice and send emails after 3D Secure Payment succeeded
            $paymentObject = $event->data->object;
            $invoice = Invoice::find($paymentObject->metadata->invoice_id);

            if($invoice->payment_status === 0) {

                $invoice->payment_status = 1;
                $invoice->save();

                $type = $paymentObject->metadata->type;

                if($type === "coach") {

                    $student = User::find($paymentObject->metadata->user_id);

                    $paymentData = [
                        'student_name' => $invoice->client_name,
                        'amount' => $invoice->total_amount,
                        'currency' => $invoice->invoice_currency,
                        'invoice_id' => $invoice->id,
                        'coach_name' => $invoice->seller_name,
                        'date' => now()->format('Y-m-d H:i:s')
                    ];

                    // Email to student
                    Mail::to($student->email)->send(new PaymentConfirmation($paymentData));
                    
                    // Email to coach
                    Mail::to($invoice->seller_email)->send(new PaymentConfirmation($paymentData, true));
                }

            }
        }


        return response()->json(['received' => true]);
    }

}
