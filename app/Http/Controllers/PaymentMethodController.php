<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{

    /*public function index()
    {
        $paymentMethods = Auth::user()->paymentMethods;

        return view('payment_methods.index', compact('paymentMethods'));
    }*/

    public function create()
    {
        return view('payment_methods.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'details' => 'nullable|array'
        ]);

        PaymentMethod::create([
            'type' => $data['type'],
            'details' => $data['details'],
            'user_id' => auth()->id(),  // Associe l'ID de l'utilisateur connecté
        ]);

        return redirect()->back()->with('success', 'Payment method created successfully!');
    }

    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::find($id);

        if (!$paymentMethod) {
            return redirect()->back()->with('error', 'Payment method not found.');
        }

        $paymentMethod->delete();

        return redirect()->back()->with('success', 'Payment method deleted successfully!');
    }
}
