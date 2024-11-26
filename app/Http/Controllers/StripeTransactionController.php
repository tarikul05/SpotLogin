<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Invoice;
use Stripe\Charge;
use Illuminate\Support\Facades\Auth;

class StripeTransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntents = PaymentIntent::all([
            'customer' => $user->stripe_id,
            'limit' => 100,
        ])->data; 

        $transactions = collect($paymentIntents)->map(function ($transaction) {
            if (isset($transaction->payment_method)) {
                $paymentMethod = PaymentMethod::retrieve($transaction->payment_method);
                if (isset($transaction->invoice)) {
                    $invoice = Invoice::retrieve($transaction->invoice);
                    $transaction->invoice_pdf = $invoice->invoice_pdf;
                } else {
                    $charge = Invoice::retrieve($transaction->latest_charge);
                    $transaction->invoice_pdf = $charge->receipt_url;
                }
                $transaction->card_last4 = $paymentMethod->card->last4 ?? null;
                $transaction->card_brand = $paymentMethod->card->brand ?? null;
            } else {
                $transaction->card_last4 = null;
                $transaction->card_brand = null;
            }
            return $transaction;
        });

        return view('pages.transactions.index', [
            'transactions' => $transactions,
            'user' => $user,
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
