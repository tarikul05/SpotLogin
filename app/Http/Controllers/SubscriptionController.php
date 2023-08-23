<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Subscription as CashierSubscription;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Laravel\Cashier\PaymentMethod;
use Carbon\Carbon;
use Exception;
use DateTime;

use Stripe\Exception\ApiErrorException;
use Stripe\Subscription;

class SubscriptionController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        parent::__construct();
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function index()
    {
        try{
            $user = auth()->user();
            $customers = $this->stripe->customers->all();
            $subscribers = [];
            $invoice_obj = null;
            foreach($customers as $customer){
                $subscription = $this->stripe->subscriptions->all(['customer' => $customer->id]);
                if(!empty($subscription['data'])){
                    $subscriber = (object) $subscription['data'][0];
                    $product_object = $this->stripe->products->retrieve(
                        $subscriber->plan['product'],
                        []
                    );
                    $invoice = $this->stripe->invoices->retrieve(
                        $subscriber->latest_invoice,
                        []
                    );
                    $subscribers[] = [
                        'billing_cycle_anchor' => $subscriber->billing_cycle_anchor,
                        'current_period_end' => $subscriber->current_period_end,
                        'current_period_start' => $subscriber->current_period_start,
                        'customer' => $customer->name,
                        'days_until_due' => $subscriber->days_until_due,
                        'latest_invoice' => $subscriber->latest_invoice,
                        'start_date' => $subscriber->start_date,
                        'amount' => $subscriber->plan['amount'],
                        'amount_decimal' => $subscriber->plan?$subscriber->plan['amount_decimal']:'',
                        'interval' => $subscriber->plan?$subscriber->plan['interval']:'',
                        'product' => $subscriber->plan['product'],
                        'plan_name' => $product_object->name,
                        'email' => $customer->email,
                        'user_name' => $customer->name,
                        'invoice_url' => $invoice?$invoice->hosted_invoice_url:'',
                    ];
                }else{
                    $subscribers[] = [
                        'billing_cycle_anchor' => '',
                        'current_period_end' => '',
                        'current_period_start' => '',
                        'customer' => '',
                        'days_until_due' => '',
                        'latest_invoice' => '',
                        'start_date' => '',
                        'amount' => '',
                        'amount_decimal' => '',
                        'interval' => '',
                        'product' => '',
                        'plan_name' => '',
                        'email' => $customer->email,
                        'user_name' => $customer->name,
                        'invoice_url' => '',
                    ];
                }
            }
            return view('pages.subscribers.list', compact('subscribers'));
        } catch (Exception $e) {
            // throw error message
        }
    }

    public function upgradePlan(Request $request)
    {
        try {
            $user = auth()->user();
            $user_per = $request->user();
            $subscription = null;
            $has_subscriber = null;
            if($user->stripe_id){
                $subscription_info = $this->stripe->subscriptions->all(['customer' => $user->stripe_id]);
                // $subscription_info = $this->stripe->subscriptions->search(['query' => 'customer:"'.$user->stripe_id.'"']);
                if(!empty($subscription_info['data'])){
                    $subscription = (object) $subscription_info['data'][0];
                }
            }
            $is_subscribed = $user->subscribed('default');
            $today_date = new DateTime();
            if (!$is_subscribed) {
                $trial_ends_date = date('F j, Y, g:i a', strtotime($user->trial_ends_at));
            } else {
                $trial_ends_date = null;
            }
            $plans = [];
            // $get_plans = $this->stripe->plans->all(); // get plan form stripe
            if ($user_per->isSchoolAdmin()) {
                $prod_id = env('stripe_school_product_id');
            }else if($user_per->isTeacherAll() || $user_per->isTeacherMedium() || $user_per->isTeacherMinimum()){
                $prod_id = env('stripe_teacher_product_id');
            }else if($user_per->isTeacherAdmin()){
                $prod_id = env('stripe_single_coach_product_id');
            }else{
                $prod_id = '';
            }
            if($prod_id){
                $get_plans = $this->stripe->prices->search(['query' => 'product:"'.$prod_id.'"']);
                foreach ( $get_plans as $get_plan) {
                    $product_object = $this->stripe->products->retrieve(
                        $get_plan->product,
                        []
                    );
                    $plans[] = [
                        'id' => $get_plan->id,
                        'nickname' => $get_plan->nickname,
                        'amount' => $get_plan->unit_amount_decimal / 100,
                        'interval' => $get_plan->recurring->interval,
                        'interval_count' => $get_plan->recurring->interval_count,
                        'metadata' => $get_plan->metadata,
                        'nickname' => $get_plan->nickname,
                        'plan_name' => $product_object,
                    ];
                }
            }
            $intent = $request->user()->createSetupIntent();
            return view('pages.subscribers.upgrade', compact('intent','user','is_subscribed', 'trial_ends_date', 'plans', 'subscription'));
        } catch (Exception $e) {
            // throw error message
        }
    }

    public function hasSubscriberInfo($subsciberinfo = null, $plan_id = null){
        $subs_info = [];
        if($subsciberinfo){
            if($subsciberinfo['plan']['id'] == $plain_id){
                $subs_info= [
                    'subscribed' => true,
                    'billing_cycle_anchor' => $subsciberinfo['billing_cycle_anchor']
                ];
            }else{
                $subs_info= [
                    'subscribed' => false,
                    'billing_cycle_anchor' => null
                ];
            }
        }
        return $subs_info;
    }

    // public function subscribePlanList(Request $request)
    // {
    //     try {
    //         $user = auth()->user();
    //         $plans = [];
    //         $get_plans = $this->stripe->plans->all(); // get plan form stripe
    //         foreach ($get_plans as $get_plan) {
    //             $product_object = $this->stripe->products->retrieve(
    //                 $get_plan->product,
    //                 []
    //             );
    //             $plans[] = [
    //                 'id' => $get_plan->id,
    //                 'amount' => $get_plan->amount / 100,
    //                 'interval' => $get_plan->interval,
    //                 'interval_count' => $get_plan->interval_count,
    //                 'metadata' => $get_plan->metadata,
    //                 'nickname' => $get_plan->nickname,
    //                 'plan_name' => $product_object,
    //             ];
    //         }
    //         return view('pages.subscribers.list', compact('plans', 'user'));
    //     } catch (Exception $e) {
    //         // throw error message
    //     }
    // }

    public function supscribePlan( Request $request, $plain_id)
    {
        try {
            $single_plan_info = $this->stripe->plans->retrieve($plain_id, []);
            $product_object = $this->stripe->products->retrieve(
                $single_plan_info->product,
                []
            );
            $intent = $request->user()->createSetupIntent();
            return view('pages.subscribers.single_subs', compact('single_plan_info', 'product_object', 'intent'));
        } catch (Exception $e) {
            // throw error message
        }
    }

    public function supscribePlanStore(Request $request)
    {
        try {
            $user = $request->user();
            $ends_at = auth()->user()->trial_ends_at;
            $plan_id = $request->plan;
            $plan_name = $request->plan_name;

            if ($user->subscribed('default')) {
                return redirect()->route('agenda')->with('success', 'You have already subscribed to ' . $plan_name);
            }

            $paymentMethod = $request->paymentMethod;
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);

            $trialEndsAt = Carbon::parse($ends_at);
            $now = now();

            $coupon_code = $request->input('coupon_code');

            if ($trialEndsAt->isPast()) {
                // Si la date de fin du trial est dans le passé, on démarre le plan premium immédiatement
                $subscription = $user->newSubscription('default', $plan_id);

                if (!empty($coupon_code)) {
                    $subscription->withCoupon($coupon_code); // Appliquer le coupon
                }

                $subscription->create($paymentMethod, [
                    'email' => $user->email,
                    'name' => $request->card_holder_name,
                ], [
                    'metadata' => ['note' => $user->email . ', ' . $request->card_holder_name],
                ]);
            } else {
                // Sinon, on utilise la période d'essai jusqu'à la date de fin du trial
                $subscription = $user->newSubscription('default', $plan_id)
                ->trialUntil($trialEndsAt->endOfDay());

                if (!empty($coupon_code)) {
                    $subscription->withCoupon($coupon_code); // Appliquer le coupon
                }

                $subscription->create($paymentMethod, [
                    'email' => $user->email,
                    'name' => $request->card_holder_name,
                ], [
                    'metadata' => ['note' => $user->email . ', ' . $request->card_holder_name],
                ]);
            }

            $user->trial_ends_at = null;
            $user->save();

            return redirect()->route('profile.plan')->with('success', 'Congratulations, you have successfully subscribed to our ' . $plan_name);
        } catch (InvalidRequestException $e) {
            if (strpos($e->getMessage(), 'No such coupon:') !== false) {
                // L'erreur est liée à un coupon invalide
                return redirect()->back()->with('error', 'The provided coupon code is invalid. Please try again.');
            } else {
                // Autres erreurs liées à InvalidRequestException
                return redirect()->back()->with('error', 'An error occurred while processing your subscription. Please try again.');
            }
        } catch (IncompletePayment $exception) {
            return redirect()->back()->with('error', $exception->getCode() . ', ' . $exception->getMessage());
        } catch (\Exception $e) {
            // Gérer d'autres exceptions non liées à Stripe ici
            if (strpos($e->getMessage(), 'No such coupon:') !== false) {
                // L'erreur est liée à un coupon invalide
                return redirect()->back()->with('error', 'The provided coupon code is invalid. Please try again.');
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function singlePayment(Request $request, $payment_id = null){
        try {
            if(isset(($_GET['payment_method']))){
                $get_payment_method = $request->get('payment_method');
            }else{
                $get_payment_method = null;
            }
            $user = auth()->user();
            $plan = $user->subscriptions()->active()->first();
            $payment_methods = $user->paymentMethods();
            $intent = $request->user()->createSetupIntent();
            return view('pages.subscribers.single_payment', compact('intent','payment_methods','get_payment_method'));
        } catch ( Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function upgradeNewPlan(Request $request, $payment_id = null){
        $user = auth()->user();
        $plain_id = $request->payment_id;
        $single_plan_info = $this->stripe->plans->retrieve($plain_id, []);
        $product_object = $this->stripe->products->retrieve(
            $single_plan_info->product,
            []
        );
        $payment_methods = $user->paymentMethods();
        return view('pages.subscribers.plan_upgrade', compact('payment_methods','single_plan_info','product_object'));
    }

    public function storeUpgradePlan(Request $request){
        try{
            $user = auth()->user();
            $plain_id = $request->price_duration;
            $plan_name = $request->plan_name;
            $user->subscription('default')->swap($plain_id);
            return redirect()->route('agenda')->with('success', 'Congratulations, you have succesully upgrade to our ' . $plan_name);
        } catch ( Exception $exception) {
            // throw error
            // return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function storeSinglePayment(Request $request, $payment_id = null){
        try {
            $user = auth()->user();
            if($request->get('has_payment') === 'new_card'){
                $payment_methods = $request->get('paymentMethod');
            }else{
                $payment_methods = $request->get('has_payment');
            }
            $plan = $user->subscriptions()->active()->first();
            $plan_id = $plan->stripe_price;
            $single_plan_info = $this->stripe->plans->retrieve($plan_id, []);
            $product_object = $this->stripe->products->retrieve(
                $single_plan_info->product,
                []
            );
            $price = $product_object->price;
            $user->charge($price, $payment_methods);
            return redirect()->route('agenda')->with('success', 'Your payment successfully complete');
        } catch ( Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function cancelPlan(Request $request) {
        try{
            $user = auth()->user();
            $subscription = null;
            if($user->stripe_id){
                $subscription_info = $this->stripe->subscriptions->all(['customer' => $user->stripe_id])->toArray();
                if(!empty($subscription_info['data'])){
                    $subscription = $subscription_info['data'][0];
                    /*$this->stripe->subscriptions->cancel(
                        $subscription['id'],
                    );*/
                    $this->stripe->subscriptions->update(
                    $subscription['id'],
                    ['cancel_at_period_end' => true]
                    );
                }
            }
            return redirect()->route('agenda')->with('success', 'Your subscription is cancelled !');
        }catch(Exception $exception){
            //test
        }
    }



    public function mySubscription(Request $request){
        try{
            $user = auth()->user();
            $subscription = null;
            $product_object = null;
            if($user->stripe_id){
                $subscription_info = $this->stripe->subscriptions->all(['customer' => $user->stripe_id])->toArray();
                if(!empty($subscription_info['data'])){
                    $subscription = $subscription_info['data'][0];
                    $product_object = $this->stripe->products->retrieve(
                        $subscription['plan']['product'],
                        []
                    );
                }
            }
            // dd($subscription);
            return view('pages.subscribers.my_subscription', compact('subscription','product_object','user'));
        }catch(Exception $exception){
            // throw error
        }
    }
}
