<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Subscription as CashierSubscription;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Laravel\Cashier\PaymentMethod;
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

    }

    public function upgradePlan(Request $request)
    {
        try {
            $user = auth()->user();
            $is_subscribed = $user->subscribed('default');
            $today_date = new DateTime();
            if (!$is_subscribed) {
                $trial_ends_date = date('F j, Y, g:i a', strtotime($user->trial_ends_at));
            } else {
                $trial_ends_date = null;
            }
            $intent = $request->user()->createSetupIntent();
            return view('pages.subscribers.upgrade', compact('intent', 'trial_ends_date'));
        } catch (Exception $e) {
            // throw error message
        }
    }

    public function subscribePlanList(Request $request)
    {
        try {
            $user = auth()->user();
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
            return view('pages.subscribers.list', compact('plans', 'user'));
        } catch (Exception $e) {
            // throw error message
        }
    }

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
}
