<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function createCustomer(Request $request)
    {
        $email = $request->input('email');
        $source = $request->input('source'); // Token generated by Stripe.js or Checkout

        $customer = $this->stripe->createCustomer($email, $source);

        return response()->json($customer);
    }

    public function chargeCustomer(Request $request)
    {
        $customerId = $request->input('customer_id');
        $amount = $request->input('amount');

        $charge = $this->stripe->chargeCustomer($customerId, $amount);

        return response()->json($charge);
    }
}
