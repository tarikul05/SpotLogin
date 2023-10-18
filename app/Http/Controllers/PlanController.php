<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanController extends Controller
{
    protected $stripe;

    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function index()
    {
        $plans = $this->stripe->products->all();
        foreach ($plans as $plan) {
            $price = $this->stripe->prices->retrieve($plan->default_price, []);

            $plan->price = $price;
        }

        return view('pages.admin.plans')
        ->with(compact('plans'));
    }

    public function showCreateForm()
    {
        return view('pages.admin.add-plan');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'product' => 'required',
            'price' => 'required',
            'currency' => 'required',
            'unit_amount_decimal' => 'required',
            'interval' => 'required',
            'interval_count' => 'required',
            'tax_behavior' => 'required',
        ]);



        try {
            $product = $this->stripe->products->retrieve($data['product']);

            // Création du plan avec les informations de default_price_data
            $createdPlan = $product->plans->create([
                'nickname' => $data['name'],
                'default_price_data' => [
                    'currency' => $data['currency'],
                    'unit_amount_decimal' => $data['unit_amount_decimal'] * 100, // Montant en centimes
                    'recurring' => [
                        'interval' => $data['interval'],
                        'interval_count' => $data['interval_count'],
                    ],
                    'tax_behavior' => $data['tax_behavior'],
                ],
            ]);

            return redirect()->route('plans.create')->with('success', 'Plan créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('plans.create')->with('error', 'Erreur lors de la création du plan : ' . $e->getMessage());
        }
    }
}
