<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Refund;
use Stripe\Exception\ApiErrorException;
use Stripe\BalanceTransaction;
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

    public function chargeCustomer($customerId, $amount, $source)
    {
        try {
            return \Stripe\Charge::create([
                'customer' => $customerId,
                'amount' => $amount,
                'currency' => 'AUD',
                'source' => $source, // Ensure this is passed
                'description' => 'Order Payment',
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
    


    public function searchCustomerByEmail($email)
    {
        $customers = Customer::search(['query' => 'email:\''.$email.'\'','limit'=>1]);
        if (count($customers->data) > 0) {
            return $customers->data[0]; // Return the list of customers
        } else {
            return false; // No customers found
        }
    }

    public function refundOrder($paymentId)
    {
        try {
            return Refund::create([
                'charge' => $paymentId,
            ]);

        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }

    public function retrivePaymentDetails($paymentId)
    {
       try {
        $charge = Charge::retrieve($paymentId);
        if(isset($charge->balance_transaction) && !empty($charge->balance_transaction)){
            $balanceTransaction = BalanceTransaction::retrieve($charge->balance_transaction);
            return $balanceTransaction;
        }else{
            return null;
        }
       } catch (ApiErrorException $e) {
           return $e->getMessage();
       }
    }

}
