<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Invoice;
use Illuminate\Support\Facades\Auth;

class StripeTransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Configure la clé Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Récupère les Payment Intents liés au client
        $paymentIntents = PaymentIntent::all([
            'customer' => $user->stripe_id,
            'limit' => 100,
        ])->data; // Récupère le tableau brut des Payment Intents

        // Convertit en collection pour mapper
        $transactions = collect($paymentIntents)->map(function ($transaction) {
            // Vérifie si une méthode de paiement est associée
            if (isset($transaction->payment_method)) {
                // Récupère les détails de la méthode de paiement
                $paymentMethod = PaymentMethod::retrieve($transaction->payment_method);

                if (isset($transaction->invoice)) {
                $invoice = Invoice::retrieve($transaction->invoice);
                // Ajoute les détails à l'Invoice
                $transaction->invoice_pdf = $invoice->invoice_pdf;
                }

                // Ajoute les détails au Payment Intent
                $transaction->card_last4 = $paymentMethod->card->last4 ?? null;
                $transaction->card_brand = $paymentMethod->card->brand ?? null;
            } else {
                $transaction->card_last4 = null;
                $transaction->card_brand = null;
            }

            return $transaction;
        });

        // Retourne la vue avec les données
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
