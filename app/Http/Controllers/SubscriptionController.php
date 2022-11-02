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
            $subscriptions = $this->stripe->subscriptions->all();
            $subscribers = [];
            $email = null;
            $firstname = null;
            $invoice_obj = null;
            if($subscriptions['data']){
                foreach($subscriptions['data'] as $subscription){
                    $product_object = $this->stripe->products->retrieve(
                        $subscription->plan['product'],
                        []
                    );
                    $customer_obj = Cashier::findBillable($subscription->customer);
                    if($customer_obj){
                        $email = $customer_obj->email;
                        $firstname = $customer_obj->firstname;
                        $lastname = $customer_obj->lastname;
                    }
                    $invoice = $this->stripe->invoices->retrieve(
                        $subscription->latest_invoice,
                        []
                    );
                    if($invoice){
                        $invoice_obj = $invoice;
                    }
                    $subscribers[] = [
                        'billing_cycle_anchor' => $subscription->billing_cycle_anchor,
                        'current_period_end' => $subscription->current_period_end,
                        'current_period_start' => $subscription->current_period_start,
                        'customer' => $subscription->customer,
                        'days_until_due' => $subscription->days_until_due,
                        'latest_invoice' => $subscription->latest_invoice,
                        'start_date' => $subscription->start_date,
                        'amount' => $subscription->plan['amount'],
                        'amount_decimal' => $subscription->plan?$subscription->plan['amount_decimal']:'',
                        'interval' => $subscription->plan?$subscription->plan['interval']:'',
                        'product' => $subscription->plan['product'],
                        'plan_name' => $product_object->name,
                        'email' => $email,
                        'user_name' => $firstname.' '.$lastname,
                        'invoice_url' => $invoice_obj->hosted_invoice_url,
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
            $subscription = null;
            $subscription_info = $this->stripe->subscriptions->all(['customer' => $user->stripe_id])->toArray();
            if(!empty($subscription_info['data'])){
                $subscription = $subscription_info['data'][0];
            }
            $is_subscribed = $user->subscribed('default');
            $today_date = new DateTime();
            if (!$is_subscribed) {
                $trial_ends_date = date('F j, Y, g:i a', strtotime($user->trial_ends_at));
            } else {
                $trial_ends_date = null;
            }
            $plans = [];
            $get_plans = $this->stripe->plans->all(); // get plan form stripe
            foreach ($get_plans as $get_plan) {
                $product_object = $this->stripe->products->retrieve(
                    $get_plan->product,
                    []
                );
                $plans[] = [
                    'id' => $get_plan->id,
                    'amount' => $get_plan->amount / 100,
                    'interval' => $get_plan->interval,
                    'interval_count' => $get_plan->interval_count,
                    'metadata' => $get_plan->metadata,
                    'nickname' => $get_plan->nickname,
                    'plan_name' => $product_object,
                ];
            }
            $intent = $request->user()->createSetupIntent();
            return view('pages.subscribers.upgrade', compact('intent','is_subscribed', 'trial_ends_date', 'plans', 'subscription'));
        } catch (Exception $e) {
            // throw error message
        }
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
    public function supscribePlanStore(Request $request){
        try {
            $user = $request->user();
            //find reming day
            $ends_at = auth()->user()->trial_ends_at;
            // $today_date = new DateTime();
            // $ends_at = new DateTime($ends_at);
            // $day_diff = $ends_at->diff($today_date)->format("%a");
            $plan_id = $request->plan;
            $plan_name = $request->plan_name;
            // $plan = $this->stripe->plans->retrieve($plan_id, []);
            if ($user->subscribed('default')) {
                return redirect()->route('agenda')->with('success', 'already, You have subscribed ' . $plan_name);
            }
            $anchor = Carbon::parse($ends_at);
            $paymentMethod = $request->paymentMethod;
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $user->newSubscription('default', $plan_id)
                    ->trialUntil($anchor->endOfDay())
                    ->create($paymentMethod, [
                        'email' => $user->email,
                        'name'  => $request->card_holder_name,
                    ],
                    [
                        'metadata' => ['note' => $user->email.', '.$request->card_holder_name ],
                    ]
                );
            $user->trial_ends_at = NULL;
            $user->save();
            return redirect()->route('agenda')->with('success', 'Welcome, You have subscribed ' . $plan_name);
        } catch (IncompletePayment $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
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
