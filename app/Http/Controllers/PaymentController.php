<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Customer as Customer_user;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Session;
use Stripe\Customer;

class PaymentController extends Controller
{
    protected $stripe;
    protected $CartService;

    public function __construct(StripeService $stripe,CartService $CartService)
    {
        $this->stripe = $stripe;
        $this->CartService = $CartService;

    }

    public function checkout()
     {
        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        $countries = Country::find(14);
        $CartTotal = $this->CartService->getCartTotal();
        $shipping = $this->CartService->getShippingCharge();

        return view('front-end.checkout',compact('cart','CartTotal','shipping','countries'));
     }

    public function createCustomer(Request $request)
    {
        $source = $request->input('stripeToken'); // Token generated by Stripe.js or Checkout

        $fname = $request->input('fname');
        $lname = $request->input('lname');
        $street1 = $request->input('street1');
        $street2 = $request->input('street2');
        $state = $request->input('state');
        $postcode = $request->input('postcode');
        $phone = $request->input('phone');
        $suburb = $request->input('suburb');
        $email = $request->input('email');
        $username = $request->input('username');
        $password = $request->input('password');
        $stripeToken = $request->input('stripeToken');

        $ship_fname = $request->input('ship_fname');
        $ship_lname = $request->input('ship_lname');
        $ship_company = $request->input('ship_company');
        $ship_street1 = $request->input('ship_street1');
        $ship_street2 = $request->input('ship_street2');
        $ship_suburb = $request->input('ship_suburb');
        $ship_state = $request->input('ship_state');
        $ship_postcode = $request->input('ship_postcode');
        $order_comments = $request->input('order_comments');

        $address = [
            'billing_address' => [
                'fname' => $fname,
                'lname' => $lname,
                'street1' => $street1,
                'street2' => $street2,
                'state' => $state,
                'postcode' => $postcode,
                'phone' => $phone,
                'suburb' => $suburb,
                'email' => $email,
                'username' => $username,
                'password' => $password,
                'stripeToken' => $stripeToken,
            ]
        ];

        if (isset($ship_fname) || isset($ship_lname) || isset($ship_street1) || isset($ship_suburb) || isset($ship_state) || isset($ship_postcode)) {
            $address['shipping_address'] = [
                'ship_fname' => $ship_fname,
                'ship_lname' => $ship_lname,
                'ship_company' => $ship_company,
                'ship_street1' => $ship_street1,
                'ship_street2' => $ship_street2,
                'ship_suburb' => $ship_suburb,
                'ship_state' => $ship_state,
                'ship_postcode' => $ship_postcode,
                'order_comments' => $order_comments
            ];
        }
    

        $customer = Customer_user::where('email',$email)->first();

        if(!isset($customer) && empty($customer)){

            $stripeCustomer = $this->stripe->createCustomer($email, $source);

            $customer = new Customer_user;
            $customer->user_id = '1';
            $customer->customer_id = $stripeCustomer->id;
            $customer->email = $email;
            $customer->log = $stripeCustomer;
            $customer->save();

            $customer_id = $stripeCustomer->id;
        }else{
            $customer_id = $customer->customer_id;
        }

        Session::put('address', $address);

        $customerIdArr = ['id' => $customer_id];

        return response()->json($customerIdArr);
    }

    public function chargeCustomer(Request $request)
    {
        $customerId = $request->input('customer_id');
        $amount = $request->input('amount');

        $charge = $this->stripe->chargeCustomer($customerId, $amount);

        if(isset($charge) && $charge->status == 'succeeded'){
            // store data in order table
            // store items in order table
        }else{
           // store logs
        }
        // dump(Session::get('address'));
        // dd($charge);

        return response()->json($charge);
    }
}
