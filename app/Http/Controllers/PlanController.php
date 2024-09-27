<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentMethod;
use App\Models\Invoice;
use App\Models\School;
use App\Models\User;

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
        $this->stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
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

    public function stripe_bank_account(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
        $school = School::active()->find($schoolId);
        $country_code = $request->country_code;

          $accountCreate = $this->stripe->accounts->create([
            'country' => $country_code,
            'email' => $user->email,
            'company' => [
                'name' => $user->firstname . ' ' . $user->lastname,
            ],
            'individual' => [
                'first_name' => $user->firstname,
                'last_name' => $user->lastname,
                'email' => $user->email,
            ],
            'metadata' => [
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'type' => 'coach',
            ],
            'type' => 'express',
            'capabilities' => [
              'card_payments' => ['requested' => true],
              'transfers' => ['requested' => true],
            ],
            'business_type' => 'individual',
            'business_profile' => ['url' => 'https://sportlogin.app/account/?tab=5'],
          ]);

          $account = $this->stripe->accountLinks->create([
            'account' => $accountCreate->id,
            'refresh_url' => 'https://sportlogin.app/account/?tab=5',
            'return_url' => 'https://sportlogin.app/account/?tab=5&confirmation=stripe',
            'type' => 'account_onboarding',
          ]);

          $user->stripe_account_id = $accountCreate->id;
          $user->save();

        PaymentMethod::create([
            'type' => 'Stripe',
            'details' => [
                'account_number' => $accountCreate->id
            ],
            'user_id' => $user->id,
        ]);

          return response()->json($account);
    }

    public function continue_stripe_bank_account(Request $request)
    {
        $user = Auth::user();

        $account_id = $user->stripe_account_id;

          $account = $this->stripe->accountLinks->create([
            'account' => $account_id,
            'refresh_url' => 'https://sportlogin.app/account?tab=5',
            'return_url' => 'https://sportlogin.app/account?tab=5&confirmation=stripe',
            'type' => 'account_onboarding',
          ]);

          return response()->json($account);

    }

    
    public function createPaymentIntentForCoach(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->selectedSchoolId();
        $school = School::active()->find($schoolId);

        if (!$request->stripe_payment_method_id) {
            return response()->json(['error' => 'No payment method found'], 400);
        }

        $invoice = Invoice::find($request->invoice_id);
        $coachOfInvoice = User::Where(['person_type' => 'App\Models\Teacher', 'person_id' => $invoice->seller_id])->first();

        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $invoice->total_amount * 100,
            'currency' => $invoice->invoice_currency,
            'customer' => $user->stripe_id,
            'payment_method' => $request->stripe_payment_method_id,
            'payment_method_types' => ['card'],
            'confirm' => true, 
            'metadata' => [
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'invoice_id' => $invoice->id,
                'type' => 'coach',
            ],
            'transfer_data' => [
                'destination' => $coachOfInvoice->stripe_account_id, 
            ],
        ]);

        if($paymentIntent->status === 'succeeded') {
            $invoice->payment_status = 1;
            $invoice->save();
        }

        return response()->json(['clientSecret' => $paymentIntent->client_secret, 'status' => $paymentIntent->status]);
    }
}
