<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartData;
use App\Models\Country;
use App\Models\Customer as Customer_user;
use App\Services\CartService;
use App\Services\AfterPayService;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\ProductStock;
use App\Models\State;
use App\Models\AfterPayLogs;
use App\Models\OrderDetail;
use App\Models\OrderBillingDetails;
use Illuminate\Support\Facades\Session;
use App\Mail\Order\MakeOrder;
use App\Mail\AdminNotifyOrder;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    protected $stripe;
    protected $CartService;
    protected $AfterPayService;

    public function __construct(StripeService $stripe,CartService $CartService,AfterPayService $AfterPayService)
    {
        $this->stripe = $stripe;
        $this->CartService = $CartService;
        $this->AfterPayService = $AfterPayService;

    }

    public function checkout()
     {
        $user_address = '';
        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
            $user_address = UserDetails::where('user_id',$auth_id)->first();
        }else{
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        $countries = Country::find(14);
        $CartTotal = $this->CartService->getCartTotal();
        $shipping = $this->CartService->getShippingCharge();

        $page_content = ["meta_title"=>config('constant.pages_meta.checkout.meta_title'),"meta_description"=>config('constant.pages_meta.checkout.meta_description')]; 

        return view('front-end.checkout',compact('cart','CartTotal','shipping','countries','page_content','user_address'));
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
        $company_name = $request->input('company_name');
        // $stripeToken = $request->input('stripeToken');

        $ship_fname = $request->input('ship_fname');
        $ship_lname = $request->input('ship_lname');
        $ship_company = $request->input('ship_company');
        $ship_street1 = $request->input('ship_street1');
        $ship_street2 = $request->input('ship_street2');
        $ship_suburb = $request->input('ship_suburb');
        $ship_state = $request->input('ship_state');
        $ship_postcode = $request->input('ship_postcode');
        $isShippingAddress = $request->input('isShippingAddress');
        $order_comments = $request->input('order_comments');

        $state_name = State::whereId($state)->select('name')->first();
        $ship_state_name = State::whereId($ship_state)->select('name')->first();

        $address = [
            'fname' => $fname,
            'lname' => $lname,
            'street1' => $street1,
            'street2' => $street2,
            'state' => $state_name->name ?? '',
            'company_name' => $company_name ?? '',
            'country_region' => config('constant.default_country'),
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
                'isShippingAddress' => isset($isShippingAddress) && ($isShippingAddress == true) ? $isShippingAddress : false,
                'ship_country_region' => config('constant.default_country'),
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
            $coupon_id = (isset($cartTotal['coupon_id']) && !empty($cartTotal['coupon_id'])) ? $cartTotal['coupon_id'] : null;
       
            $order = Order::create([
                'user_id' => isset(Auth::user()->id) ? Auth::user()->id : null,
                'user_session_id' => isset(Auth::user()->id) ? null : $session_id,
                'order_number' => $orderNumber,
                'coupon_id' => $coupon_id,
                'coupon_code' => $coupon_code['code'] ?? '',
                'discount' => $coupon_discount ?? 0,
                'sub_total' => $subtotal ?? 0,
                'shipping_charge' => $shipping_amount ?? 0,
                'total' => $cart_total ?? 0,
                'payment_id' => (isset($charge->id)) ? $charge->id : "",
                'is_paid' => (isset($charge->captured)) ? $charge->captured : false,
                'payment_status' => $charge->status,
                'order_status' => "0",
            ]);

            foreach ($cart->items as $item) {

                if ($item->product_type == 'hand_craft') {
                    $slug = 'hand-craft';
                
                    $hand_craft_cat = $this->CartService->getProductStock($slug, $item->product_id);
                
                    if ($hand_craft_cat && $hand_craft_cat->getProductStock) {
                        $productStock = $hand_craft_cat->getProductStock;
                
                        $stock_qty = $productStock->qty;
                        $product_category_type_id = $productStock->product_category_type_id;
                        $hand_craft_category_id = $productStock->category_id;
                
                        ProductStock::where([
                            'product_category_type_id' => $product_category_type_id,
                            'category_id' => $hand_craft_category_id,
                            'product_id' => $item->product_id
                        ])->decrement('qty', $item->quantity);
                    }
                }
                
                if($item->product_type == "gift_card" || $item->product_type == "photo_for_sale" || $item->product_type == "hand_craft"){
                    $product_price =  number_format($item->product_price, 2);
                    $item_price = $item->quantity * $product_price;
                    $sale_on = 0;
                    $sale_price=null;
                }
                else{
                    $product_sale_price = $this->CartService->getProductSalePrice($item->product_id);
                    $product_price =number_format($product_details->product_price, 2);
                    
                    if(isset($product_sale_price) && !empty($product_sale_price)){
                        $item_price = $item->quantity * $product_sale_price;
                        $sale_price = number_format($product_sale_price, 2);
                        $sale_on = 1;
                    }else{
                        $item_price = $item->quantity * $product_price;
                        $sale_on = 0;
                        $sale_price=null;
                    }
                    
                }

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'selected_images' => $item->selected_images,
                    'price' => $item_price,
                    'product_type' => $item->product_type ?? null,
                    'product_desc' => $item->product_desc,
                    'product_price' => $product_price ?? 0,
                    'sale_price' => $sale_price,
                    'sale_on' => $sale_on
                ]);
            }

            if(Session::has('order_address')){

                $order_address = Session::get('order_address');

                if (Auth::check() && !empty(Auth::user())) {
                    $auth_id = Auth::user()->id;
                    $user_details = UserDetails::where('user_id',$auth_id)->first();
                    if (!$user_details) {
                        $order_address['user_id'] = $auth_id;
                        UserDetails::create($order_address);
                        unset($order_address['user_id']);
                    }
                }

                $order_address['order_id'] = $order->id;
                OrderBillingDetails::create($order_address);
            }

            CartData::where('cart_id',$cart->id)->delete();
            Cart::where('id', $cart->id)->delete();

            
            if(isset($order) && !empty($order)){
                
                $orderDetail = $order->whereId($order->id)->with('orderDetails.product','orderBillingShippingDetails')->first();

                Mail::to($order_address['email'])->send(new MakeOrder($orderDetail));
                Mail::to(env('APP_MAIL'))->send(new AdminNotifyOrder($orderDetail));
            }

            Session::forget(['order_address', 'coupon','billing_details']);

            return response()->json(['error' => false,'message'=>'success','order_id'=>$order->id]);

        }else{
           return response()->json(['error' => true,'message' => 'Something went wrong']);
        }
    }

    public function thankyou($orderId){
        $page_content = ["meta_title"=>config('constant.order.thankyou.meta_title'),"meta_description"=>config('constant.order.thankyou.meta_description')];

        $order_number = Order::whereId($orderId)->select('order_number')->first();
        return view('front-end.order_thank_you',compact('order_number','page_content'));
    }

    public function showPaymentMethods()
    {
        return view('afterpay');
    }

    public function afterPayCheckout(Request $request)
    {
        $orderDetails = [
            "amount" => [
                "amount" => "0.10",
                "currency" => "AUD"
            ],
            "consumer" => [
                "phoneNumber" => "0412345678",
                "givenNames" => "Test",
                "surname" => "Consumer",
                "email" => "test@example.com"
            ],
            "billing" => [
                "name" => "Test Consumer",
                "line1" => "123 Fake Street",
                "line2" => "Unit 4",
                "suburb" => "Realville",
                "state" => "NSW",
                "postcode" => "2000",
                "countryCode" => "AU",
                "phoneNumber" => "0412345678"
            ],
            "shipping" => [
                "name" => "Test Shipping Consumer",
                "line1" => "123 Fake Street",
                "line2" => "",
                "suburb" => "Realville",
                "state" => "NSW",
                "postcode" => "2000",
                "countryCode" => "AU",
                "phoneNumber" => "0412345678"
            ],
            "courier" => [
                "shippedAt" => "2024-08-30",
                "name" => "DHL",
                "tracking" => "ABC123XYZ",
                "priority" => "STANDARD"  // Changed to a valid value
            ],
            "description" => "Order for consumer",
            "items" => [
                [
                    "name" => "Sample Item",
                    "sku" => "ITEM001",
                    "quantity" => 1,
                    "price" => [
                        "amount" => "0.01",
                        "currency" => "AUD"
                    ]
                ]
            ],
            "discounts" => [
                [
                    "displayName" => "Summer Discount",
                    "amount" => [
                        "amount" => "0.01",
                        "currency" => "AUD"
                    ]
                ]
            ],
            "merchant" => [
                "redirectConfirmUrl" => route('checkout.success'),
                "redirectCancelUrl" => route('checkout.cancel'),
            ],
            "merchantReference" => "order_reference_001",
            "taxAmount" => [
                "amount" => "0.00",
                "currency" => "AUD"
            ],
            "shippingAmount" => [
                "amount" => "0.01",
                "currency" => "AUD"
            ]
        ];
        
        $response = $this->AfterPayService->charge($orderDetails);
        
        if (isset($response['redirectCheckoutUrl']) && !empty($response['redirectCheckoutUrl'])) {
            $token = $response['token'];
            Session::put('afterpay_token', $token);
            return response()->json(['error'=>false,'data' => $response['redirectCheckoutUrl']]);
        }
        return response()->json(['error'=>true,'data' => $response['error'] ?? 'Error processing Afterpay payment.']);
    }

    public function afterpaySuccess()
    {
        $token = Session::get('afterpay_token');

        if (!$token) {
            return redirect()->route('checkout')->with('error', 'Session expired or invalid.');
        }

        $response = $this->AfterPayService->validateAfterpayOrder($token);

        if (isset($response['status']) && $response['status'] === 'APPROVED') {

            $log = new AfterPayLogs;
            $log->logs = json_encode($response) ?? '';
            $log->save();

            Session::forget(['order_address', 'coupon','billing_details','afterpay_token']);
            return redirect()->route('order.success');
        } else {
            return redirect()->route('checkout')->with('error', 'Payment failed or was canceled.');
        }        
    }

    public function afterpayCancel()
    {
        return redirect()->route('checkout')->with('error', 'Payment was canceled. Please try again.');
    }

    public function orderSuccess(){
        $page_content = ["meta_title"=>config('constant.order.thankyou.meta_title'),"meta_description"=>config('constant.order.thankyou.meta_description')];
        return view('afterpay_order_thankyou',compact('page_content'));
    }
}
