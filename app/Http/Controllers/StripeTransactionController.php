<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Auth;

class StripeTransactionController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntents = PaymentIntent::all([
            'customer' => $user->stripe_id,
            'limit' => 100 
        ]);

        return view('pages.transactions.index', [
            'transactions' => $paymentIntents->data,
            'user' => $user 
        ]);
    }

    public function adminIndex($userId)
    {

        $user = \App\Models\User::findOrFail($userId);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntents = PaymentIntent::all([
            'customer' => $user->stripe_id,
            'limit' => 100 
        ]);

        return view('pages.transactions.index', [
            'transactions' => $paymentIntents->data,
            'user' => $user 
        ]);
    }
}
