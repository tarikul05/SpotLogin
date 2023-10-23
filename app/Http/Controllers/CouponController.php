<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CouponController extends Controller
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
        $this->middleware('permission:superadmin');
    }

    public function index()
    {
        $coupons = $this->stripe->coupons->all();

        return view('pages.admin.coupons')
        ->with(compact('coupons'));
    }

    public function showCreateForm()
    {
        return view('pages.admin.add-coupon');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'coupon_type' => 'required',
            'amount_or_percent' => 'required|numeric',
            'duration' => 'required',
            'duration_in_months' => 'nullable|numeric',
        ]);

        $couponData = [
            'name' => $data['name'],
        ];

        if ($data['duration'] === 'repeating') {
            $couponData['duration'] = 'repeating';
            $couponData['duration_in_months'] = $data['duration_in_months'];
        }

        if ($data['coupon_type'] === 'percent_off') {
            $couponData['percent_off'] = $data['amount_or_percent'];
        } elseif ($data['coupon_type'] === 'amount_off') {
            $couponData['amount_off'] = $data['amount_or_percent'] * 100; // Amount is in cents
        }

        try {
            $this->stripe->coupons->create($couponData);
        } catch (\Exception $e) {
            return redirect()->route('coupons.create')->with('error', 'Erreur lors de la création du coupon : ' . $e->getMessage());
        }

        return redirect()->route('coupon.index')->with('success', 'Coupon créé avec succès.');
    }
}
