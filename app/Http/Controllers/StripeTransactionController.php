<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeTransactionController extends Controller
{
    public function index($userId)
    {
        // Récupérer l'utilisateur à partir de son ID
        $user = \App\Models\User::findOrFail($userId);

        // Initialiser Stripe avec votre clé API
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Récupérer les PaymentIntents pour le customer_id de Stripe
        $paymentIntents = PaymentIntent::all([
            'customer' => $user->stripe_id,
            'limit' => 100 // Limite de 100 transactions pour l'exemple
        ]);

        // Retourner les PaymentIntents à la vue
        return view('pages.transactions.index', [
            'transactions' => $paymentIntents->data, // Envoyer les PaymentIntents à la vue
            'user' => $user // Passer l'utilisateur pour afficher ses informations
        ]);
    }
}
