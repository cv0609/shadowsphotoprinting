<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartData;
use App\Models\Country;
use App\Models\Customer as Customer_user;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\State;
use App\Models\OrderDetail;
use App\Models\OrderBillingDetails;
use Illuminate\Support\Facades\Session;

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

        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        }else{
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

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
        // $stripeToken = $request->input('stripeToken');

        $ship_fname = $request->input('ship_fname');
        $ship_lname = $request->input('ship_lname');
        $ship_company = $request->input('ship_company');
        $ship_street1 = $request->input('ship_street1');
        $ship_street2 = $request->input('ship_street2');
        $ship_suburb = $request->input('ship_suburb');
        $ship_state = $request->input('ship_state');
        $ship_postcode = $request->input('ship_postcode');
        $order_comments = $request->input('order_comments');

        $state_name = State::whereId($state)->select('name')->first();
        $ship_state_name = State::whereId($ship_state)->select('name')->first();

        $address = [
            'fname' => $fname,
            'lname' => $lname,
            'street1' => $street1,
            'street2' => $street2,
            'state' => $state_name->name ?? '',
            'postcode' => $postcode,
            'phone' => $phone,
            'suburb' => $suburb,
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ];

        // Check if shipping details are provided
        if (isset($ship_fname) || isset($ship_lname) || isset($ship_street1) || isset($ship_suburb) || isset($ship_state) || isset($ship_postcode)) {
            $address += [ // Use the += operator to merge arrays
                'ship_fname' => $ship_fname,
                'ship_lname' => $ship_lname,
                'ship_company' => $ship_company,
                'ship_street1' => $ship_street1,
                'ship_street2' => $ship_street2,
                'ship_suburb' => $ship_suburb,
                'ship_state' => $ship_state_name->name ?? '',
                'ship_postcode' => $ship_postcode,
                'order_comments' => $order_comments
            ];
        }

        $is_exist = $this->stripe->searchCustomerByEmail($email);

        if(isset($is_exist) && $is_exist == false){

            $stripeCustomer = $this->stripe->createCustomer($email, $source);
            $customer_id = $stripeCustomer->id;

        }else{
            $customer_id = $is_exist->id;
        }

        Session::put('order_address', $address);

        $customerIdArr = ['id' => $customer_id];

        return response()->json($customerIdArr);
    }

    public function chargeCustomer(Request $request)
    {
        $customerId = $request->input('customer_id');
        $amount = $request->input('amount');

        $charge = $this->stripe->chargeCustomer($customerId, $amount);
        $cart = '';
        if(isset($charge) && ($charge->status == 'succeeded' || $charge->status == 'processing' || $charge->status == 'amount_capturable_updated' || $charge->status == 'payment_failed')){
            $orderNumber = Order::generateOrderNumber();

            if (Auth::check() && !empty(Auth::user())) {
                $auth_id = Auth::user()->id;
                $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
            }else{
                $session_id = Session::getId();
                $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
            }

            $cartTotal = $this->CartService->getCartTotal();
           
            $subtotal = $cartTotal['subtotal'] ?? 0;
            $shipping_amount = $cartTotal['shippingCharge'] ?? 0;
            $cart_total = $cartTotal['total'] ?? 0;
            $coupon_discount = $cartTotal['coupon_discount'] ?? 0;
            $coupon_code = $cartTotal['coupon_code'] ?? '';
            $coupon_id = $cartTotal['coupon_id'] ?? 0;

            $order = Order::create([
                'user_id' => isset(Auth::user()->id) ? Auth::user()->id : null,
                'user_session_id' => isset(Auth::user()->id) ? null : $session_id,
                'order_number' => $orderNumber,
                // 'coupon_id' => $coupon_id,
                'coupon_code' => $coupon_code,
                'discount' => $coupon_discount,
                'sub_total' => $subtotal,
                'shipping_charge' => $shipping_amount,
                'total' => $cart_total,
                'payment_id' => (isset($charge->id)) ? $charge->id : "",
                'is_paid' => (isset($charge->captured)) ? $charge->captured : false,
                'status' => $charge->status,
            ]);

            foreach ($cart->items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'selected_images' => $item->product->product_image,
                    'price' => $item->quantity * $item->product->product_price,
                ]);
            }

            if(Session::has('order_address')){
                $order_address = Session::get('order_address');
                $order_address['order_id'] = $order->id;
                OrderBillingDetails::create($order_address);
            }

            CartData::where('cart_id',$cart->id)->delete();
            Cart::where('id', $cart->id)->delete();

            Session::forget(['order_address', 'coupon']);

            return response()->json(['error' => false,'message'=>'success','order_id'=>$order->id]);

        }else{
           return response()->json(['error' => true,'message' => 'Something went wrong']);
        }
    }

    public function thankyou($orderId){
        return view('front-end.order_thank_you',compact('orderId'));
    }
}
