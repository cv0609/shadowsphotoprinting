<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createCustomer($email, $source)
    {
        try {
            return Customer::create([
                'email' => $email,
                'source' => $source,
            ]);
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }

    public function chargeCustomer($customerId, $amount)
    {
        try {
            return Charge::create([
                'customer' => $customerId,
                'amount' => $amount,
                'currency' => 'usd',
            ]);
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }
}
