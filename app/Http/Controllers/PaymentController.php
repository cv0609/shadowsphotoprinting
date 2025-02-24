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
use App\Models\Coupon;
use App\Models\StripeLogs;
use App\Models\State;
use App\Models\AfterPayLogs;
use App\Models\OrderDetail;
use App\Models\OrderBillingDetails;
use Illuminate\Support\Facades\Session;
use App\Mail\Order\MakeOrder;
use App\Mail\Order\GiftCardMail;
use App\Mail\AdminNotifyOrder;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $stripe;
    protected $CartService;
    protected $AfterPayService;

    public function __construct(StripeService $stripe, CartService $CartService, AfterPayService $AfterPayService)
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
            $user_address = UserDetails::where('user_id', $auth_id)->first();
        } else {
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        $shipping = $this->CartService->getShippingCharge();

        $countries = Country::find(14);
        $CartTotal = $this->CartService->getCartTotal();

        $page_content = ["meta_title" => config('constant.pages_meta.checkout.meta_title'), "meta_description" => config('constant.pages_meta.checkout.meta_description')];

        return view('front-end.checkout', compact('cart', 'CartTotal', 'shipping', 'countries', 'page_content', 'user_address'));
    }

    public function createCustomer(Request $request)
    {
        $source = $request->input('stripeToken'); // Token generated by Stripe.js or Checkout
        $state = $request->input('state');
        $email = $request->input('email');
        $ship_state = $request->input('ship_state');

        $state_name = '';
        $ship_state_name = '';

        if (isset($request->state)) {
            $state_name = State::whereId($state)->select('name')->first();
        }

        if (isset($request->ship_state)) {
            $ship_state_name = State::whereId($ship_state)->select('name')->first();
        }

        $address = $this->orderAddress($request, $state_name, $ship_state_name);

        $is_exist = $this->stripe->searchCustomerByEmail($email);

        if (isset($is_exist) && $is_exist == false) {

            $stripeCustomer = $this->stripe->createCustomer($email, $source);

            $logs_stripeCustomer = $stripeCustomer;

            $log = new StripeLogs();
            $log->logs = json_encode($logs_stripeCustomer) ?? '';
            $log->save();
    
            $customer_id = $stripeCustomer->id;
        } else {
            $customer_id = $is_exist->id;
        }

        Session::put('order_address', $address);

        $customerIdArr = ['id' => $customer_id];

        return response()->json($customerIdArr);
    }

    private function orderAddress($request, $state_name, $ship_state_name)
    {

        $address = [
            'fname' => $request->fname,
            'lname' => $request->lname,
            'street1' => $request->street1,
            'street2' => $request->street2,
            'state' => $request->state_name->name ?? '',
            'company_name' => $request->company_name ?? '',
            'country_region' => config('constant.default_country'),
            'state' => $state_name->name ?? '',
            'postcode' => $request->postcode,
            'phone' => $request->phone,
            'suburb' => $request->suburb,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
            'payment_method' => $request->payment_method,
            'shipping_charge' => $request->shipping_charge,
            'order_type' => $request->customer_order_type,
        ];

        // Check if shipping details are provided
        if (isset($request->isShippingAddress) && $request->isShippingAddress == true) {
            $address += [ // Use the += operator to merge arrays
                'ship_fname' => $request->ship_fname,
                'ship_lname' => $request->ship_lname,
                'ship_company' => $request->ship_company,
                'ship_street1' => $request->ship_street1,
                'ship_street2' => $request->ship_street2,
                'ship_suburb' => $request->ship_suburb,
                'ship_state' => $ship_state_name->name ?? '',
                'ship_postcode' => $request->ship_postcode,
                'isShippingAddress' => $request->isShippingAddress,
                'ship_country_region' => config('constant.default_country'),
                'order_comments' => $request->order_comments
            ];
        }

        return $address;
    }

    public function chargeCustomer(Request $request)
    {
        $customerId = $request->input('customer_id');
        $amount = $request->input('amount');
        $cardId = $request->input('cardId');
        $stripeToken = $request->input('stripeToken');

        $charge = $this->stripe->chargeCustomer($customerId, $amount , $stripeToken);

        $charge_logs = $charge;

        $log = new StripeLogs();
        $log->logs = json_encode($charge_logs) ?? '';
        $log->save();

        if (isset($charge) && isset($charge->status) && ($charge->status == 'succeeded' || $charge->status == 'processing' || $charge->status == 'amount_capturable_updated' || $charge->status == 'payment_failed')) {

            return $this->createOrder($charge);

        } else {
            return response()->json(['error' => true, 'message' => 'Something went wrong','data' => $charge]);
        }
    }

    private function createOrder($charge = null, $afterPay = null)
    {
        $cart = '';
        $orderNumber = Order::generateOrderNumber();

        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        } else {
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

        $payment_method = '';
        $order_type = '';
        if (Session::has('order_address')) {
            $order_address = Session::get('order_address');
            \Log::info($order_address);
            $payment_method = $order_address['payment_method'];
            $shippingCharge = $order_address['shipping_charge'];
            $order_type = $order_address['order_type'];
        }

        $order = Order::create([
            'user_id' => isset(Auth::user()->id) ? Auth::user()->id : null,
            'user_session_id' => isset(Auth::user()->id) ? null : $session_id,
            'order_number' => $orderNumber,
            'coupon_id' => $coupon_id,
            'coupon_code' => $coupon_code['code'] ?? '',
            'discount' => $coupon_discount ?? 0,
            'sub_total' => $subtotal ?? 0,
            'shipping_charge' => $shipping_amount,
            'total' => $cart_total,
            'payment_id' => ($payment_method === 'stripe')
                ? ($charge->id ?? "")
                : ($afterPay['id'] ?? ""),
            'is_paid' => ($payment_method === 'stripe')
                ? ($charge->captured ?? '')
                : ($afterPay['status'] ?? ''),
            'payment_status' => ($payment_method === 'stripe')
                ? ($charge->status ?? '')
                : ($afterPay['status'] ?? ''),
            'payment_method' => $payment_method,
            'order_status' => "0",
            'order_type' => $order_type
        ]);

        if(!empty($coupon_code)){
            $g_coupon = Coupon::where('code', $coupon_code['code'])
            ->where('is_gift_card', '1')
            ->where('is_active', 1)
            ->first();
        
            if ($g_coupon) {
                $g_coupon->update(['amount' => $g_coupon->amount - $coupon_discount]);
            }
        }

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

            // To create coupon for gift card
            $generateGiftCardCoupon = generateGiftCardCoupon(8);

            if ($item->product_type == 'gift_card') {
                $g_order_total = $shipping_amount != 0 ? $cart_total : $cart_total + $shippingCharge;
                $product_price =  number_format($item->product_price, 2);
                // Set coupon price based on the smaller value
                $coupon_price = min($product_price, $g_order_total);

                Coupon::create(['code'=>$generateGiftCardCoupon,'type' => '0','amount'=>$coupon_price,'minimum_spend'=>0.00,'maximum_spend'=>10000000000.00,'start_date'=>Carbon::now()->format('Y-m-d'),'end_date'=>Carbon::now()->addYears(3)->format('Y-m-d'),'is_active' => 1,'is_gift_card' => '1']);

                // Decode JSON into an associative array
                $giftcardDesc = json_decode($item->product_desc, true);

                // Check if 'reciept_email' exists and retrieve it
                $recieptEmail = $giftcardDesc['reciept_email'] ?? null;

                $giftcardData = [
                   'reciept_email' => $recieptEmail ?? '',
                   'code' => $generateGiftCardCoupon ?? '',
                   'image' => $item->selected_images ?? ''
                ];

                Mail::to($recieptEmail)->send(new GiftCardMail($giftcardData));
            }

            $sale_price = null;
            $sale_on = 0;

            if ($item->product_type == "gift_card" || $item->product_type == "photo_for_sale" || $item->product_type == "hand_craft") {
                $product_price =  number_format($item->product_price, 2);
                $item_price = $item->quantity * $product_price;
                $sale_on = 0;
                $sale_price = null;
            } else {
                $product_sale_price = $this->CartService->getProductSalePrice($item->product_id);
                $product_details =  $this->CartService->getProductDetailsByType($item->product_id, $item->product_type);
                $product_price = number_format($product_details->product_price, 2);

                if (isset($item->is_test_print) && ($item->is_test_print == '1')) {
                    $item_price = $item->quantity * $item->test_print_price;
                } else {
                    if (isset($product_sale_price) && !empty($product_sale_price)) {
                        $item_price = $item->quantity * $product_sale_price;
                        $sale_price = number_format($product_sale_price, 2);
                        $sale_on = 1;
                    } else {
                        $sale_on = 0;
                        $sale_price = null;
                        $item_price = $item->quantity * $product_price;
                    }
                }
            }

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'selected_images' => isset($item->is_test_print) && ($item->is_test_print == '1') ? $item->watermark_image : $item->selected_images,
                'price' => $item_price,
                'product_type' => $item->product_type ?? null,
                'product_desc' => $item->product_desc,
                'product_price' => (!empty($item->is_test_print) && $item->is_test_print == '1') ? $item->test_print_price : $product_price ?? 0,
                'sale_price' => $sale_price,
                'sale_on' => $sale_on
            ]);
        }

        if (Session::has('order_address')) {

            $order_address = Session::get('order_address');
            unset($order_address['payment_method']);
            unset($order_address['shipping_charge']);

            if (Auth::check() && !empty(Auth::user())) {
                $auth_id = Auth::user()->id;
                $user_details = UserDetails::where('user_id', $auth_id)->first();
                if (!$user_details) {
                    $order_address['user_id'] = $auth_id;
                    UserDetails::create($order_address);
                    unset($order_address['user_id']);
                }
            }

            $order_address['order_id'] = $order->id;
            OrderBillingDetails::create($order_address);
        }

        CartData::where('cart_id', $cart->id)->delete();
        Cart::where('id', $cart->id)->delete();


        if (isset($order) && !empty($order)) {

            $orderDetail = $order->whereId($order->id)->with('orderDetails.product', 'orderBillingShippingDetails')->first();

            Mail::to($order_address['email'])->send(new MakeOrder($orderDetail));
            Mail::to(env('APP_MAIL'))->send(new AdminNotifyOrder($orderDetail));
        }

        Session::forget(['order_address', 'coupon', 'billing_details', 'order_type']);

        return response()->json(['error' => false, 'message' => 'success', 'order_id' => $order->id]);
    }

    public function thankyou($orderId)
    {
        $page_content = ["meta_title" => config('constant.order.thankyou.meta_title'), "meta_description" => config('constant.order.thankyou.meta_description')];

        $order_number = Order::whereId($orderId)->select('order_number')->first();
        return view('front-end.order_thank_you', compact('order_number', 'page_content'));
    }

    public function showPaymentMethods()
    {
        return view('afterpay');
    }

    public function afterPayCheckout(Request $request)
    {
        $formData = $request->input('data');
        
        $payment_method = $formData['payment_method'] ?? '';
        $customer_order_type = $formData['customer_order_type'] ?? '';
        $shipping_charge = $formData['shipping_charge'] ?? 0;
        $fname = $formData['fname'] ?? '';
        $lname = $formData['lname'] ?? '';
        $street1 = $formData['street1'] ?? '';
        $street2 = $formData['street2'] ?? '';
        $state = $formData['state'] ?? '';
        $postcode = $formData['postcode'] ?? '';
        $phone = $formData['phone'] ?? '';
        $suburb = $formData['suburb'] ?? '';
        $email = $formData['email'] ?? '';
        $username = $formData['username'] ?? '';
        $password = $formData['password'] ?? '';
        $company_name = $formData['company_name'] ?? '';

        $ship_fname = $formData['ship_fname'] ?? '';
        $ship_lname = $formData['ship_lname'] ?? '';
        $ship_company = $formData['ship_company'] ?? '';
        $ship_street1 = $formData['ship_street1'] ?? '';
        $ship_street2 = $formData['ship_street2'] ?? '';
        $ship_suburb = $formData['ship_suburb'] ?? '';
        $ship_state = $formData['ship_state'] ?? '';
        $ship_postcode = $formData['ship_postcode'] ?? '';
        $order_comments = $formData['order_comments'] ?? '';
        $isShippingAddress = $formData['isShippingAddress'] ?? '';

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
            'payment_method' => $payment_method,
            'shipping_charge' => $shipping_charge,
            'order_type' => $customer_order_type,
        ];

        if (isset($ship_fname) || isset($ship_lname) || isset($ship_street1) || isset($ship_suburb) || isset($ship_state) || isset($ship_postcode)) {
            $address += [
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

        Session::put('order_address', $address);

        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        } else {
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        $cartTotal = $this->CartService->getCartTotal();

        // $shipping_amount = $cartTotal['shippingCharge'] ?? 0;
        $cart_total = $cartTotal['total'] ?? 0;
        $coupon_discount = $cartTotal['coupon_discount'] ?? 0;
        $coupon_code = $cartTotal['coupon_code'] ?? '';

        $itemsArray = [];

        foreach ($cart->items as $items) {
            $qty = $items->quantity;
            $product_type = $items->product_type;

            $product_details =  $this->CartService->getProductDetailsByType($items->product_id, $items->product_type);

            $product_name = $product_details->product_title;

            $product_price = ($product_type != 'shop') ? $items->product_price : $items->product->product_price;
            $sku = 'SKU-' . Str::upper(Str::random(8));

            $productPriceInCents = number_format($product_price, 2, '.', '');

            $itemsArray[] = [
                "name" => $product_name,
                "sku" => $sku,
                "quantity" => $qty,
                "price" => [
                    "amount" => $productPriceInCents,
                    // "amount" => "0.55",
                    "currency" => "AUD"
                ]
            ];
        }

        $totalAmountInCents = number_format($cart_total, 2, '.', '');
        $shippingAmountInCents = number_format($shipping_charge, 2, '.', '');
        $couponDiscountInCents = number_format($coupon_discount, 2, '.', '');

        $orderDetails = [
            "amount" => [
                "amount" => $totalAmountInCents,
                // "amount" => "0.55",
                "currency" => "AUD"
            ],
            "consumer" => [
                "phoneNumber" => $phone ?? '0412345678',
                "givenNames" => $fname ?? 'Test',
                "surname" => $lname ?? 'Test',
                "email" => $email ?? 'test@gmail.com'
            ],
            "billing" => [
                "name" =>  $fname . ' ' . $lname,
                "line1" => $street1 ?? 'address1',
                "line2" => $street2 ?? 'address2',
                "suburb" => $suburb ?? 'suberb',
                "state" => 'NSW',
                "postcode" => $postcode ?? '123456',
                "countryCode" => "AU",
                "phoneNumber" => $phone ?? '0412345678',
            ],
            "shipping" => [
                "name" => $ship_fname ? ($ship_fname . ' ' . $ship_lname) : ($fname . ' ' . $lname),
                "line1" => $ship_street1 ? $ship_street1 : $street1,
                "line2" => $ship_street2 ? $ship_street2 : $street2,
                "suburb" => $ship_suburb ? $ship_suburb : $suburb,
                "state" => 'NSW',
                "postcode" => $ship_postcode ? $ship_postcode : $postcode,
                "countryCode" => "AU",
                "phoneNumber" => $phone ?? '0412345678'
            ],
            "courier" => [
                "shippedAt" => "2024-08-30",
                "name" => "DHL",
                "tracking" => "ABC123XYZ",
                "priority" => "STANDARD"  // Changed to a valid value
            ],
            "description" => "Order for consumer",
            "items" => $itemsArray,
            "discounts" => [
                [
                    "displayName" => !empty($coupon_code) ? $coupon_code : 'Summer Discount',
                    "amount" => [
                        "amount" => $couponDiscountInCents ?? "0.00",
                        // "amount" => "5000.00",
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
                "amount" => $shippingAmountInCents ?? "0.00",
                // "amount" => "5000.00",
                "currency" => "AUD"
            ]
        ];

        $response = $this->AfterPayService->charge($orderDetails);

        $log = new AfterPayLogs;
        $log->logs = json_encode($response) ?? '';
        $log->save();

        if (isset($response['redirectCheckoutUrl']) && !empty($response['redirectCheckoutUrl'])) {
            return response()->json(['error' => false, 'data' => $response['redirectCheckoutUrl']]);
        }

        return response()->json(['error' => true, 'data' => $response['error'] ?? 'Error processing Afterpay payment.']);
    }

    public function freeOrderCheckout(Request $request){

        $formData = $request->input('data');
        
        $payment_method = $formData['payment_method'] ?? '';
        $customer_order_type = $formData['customer_order_type'] ?? '';
        $shipping_charge = $formData['shipping_charge'] ?? 0;
        $fname = $formData['fname'] ?? '';
        $lname = $formData['lname'] ?? '';
        $street1 = $formData['street1'] ?? '';
        $street2 = $formData['street2'] ?? '';
        $state = $formData['state'] ?? '';
        $postcode = $formData['postcode'] ?? '';
        $phone = $formData['phone'] ?? '';
        $suburb = $formData['suburb'] ?? '';
        $email = $formData['email'] ?? '';
        $username = $formData['username'] ?? '';
        $password = $formData['password'] ?? '';
        $company_name = $formData['company_name'] ?? '';

        $ship_fname = $formData['ship_fname'] ?? '';
        $ship_lname = $formData['ship_lname'] ?? '';
        $ship_company = $formData['ship_company'] ?? '';
        $ship_street1 = $formData['ship_street1'] ?? '';
        $ship_street2 = $formData['ship_street2'] ?? '';
        $ship_suburb = $formData['ship_suburb'] ?? '';
        $ship_state = $formData['ship_state'] ?? '';
        $ship_postcode = $formData['ship_postcode'] ?? '';
        $order_comments = $formData['order_comments'] ?? '';
        $isShippingAddress = $formData['isShippingAddress'] ?? '';

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
            'payment_method' => $payment_method,
            'shipping_charge' => $shipping_charge,
            'order_type' => $customer_order_type,
        ];

        if (isset($ship_fname) || isset($ship_lname) || isset($ship_street1) || isset($ship_suburb) || isset($ship_state) || isset($ship_postcode)) {
            $address += [
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

        Session::put('order_address', $address);
        $randomId = Str::random(10);
        $paymentResponse = [
             'id' => $randomId,
             'status' => 'APPROVED'
        ];

        if(Session::has('order_address')){
            $orderResponse = $this->createOrder($charge = null, $paymentResponse);
            $orderData = json_decode($orderResponse->getContent(), true); // Decode JSON response
            Session::forget(['order_address', 'coupon', 'billing_details', 'afterpay_token', 'order_type']);
            // return response()->json(['error' => false, 'data' => route('thankyou')]);
            return response()->json([
                'error' => false, 
                'data' => route('thankyou', ['order_id' => $orderData['order_id']])
            ]);
        }else{
            return response()->json(['error' => true, 'data' => 'Something went wrong.']);
        }
    }

    public function afterpaySuccess(Request $request)
    {
        if ($request->status == "SUCCESS" && !empty($request->orderToken)) {
            $orderToken = $request->orderToken;
            // Call Afterpay Payment API to capture the order
            $paymentResponse = $this->AfterPayService->capturePayment($orderToken);
             
            $log = new AfterPayLogs;
            $log->logs = json_encode($paymentResponse) ?? '';
            $log->save();
                
            if (isset($paymentResponse['status']) && $paymentResponse['status'] === "APPROVED") {
                $this->createOrder($charge = null, $paymentResponse);
                Session::forget(['order_address', 'coupon', 'billing_details', 'afterpay_token', 'order_type']);
                return redirect()->route('order.success');
            } else {
                return redirect()->route('checkout')->with('error', 'Payment capture failed.');
            }
        }
        return redirect()->route('checkout')->with('error', 'Payment failed or was canceled.');
    }

    public function afterpayCancel()
    {
        Session::forget(['order_address', 'coupon', 'billing_details', 'afterpay_token', 'order_type']);
        return redirect()->route('checkout')->with('error', 'Payment was cancelled. Please try again.');
    }

    public function orderSuccess()
    {
        $page_content = ["meta_title" => config('constant.order.thankyou.meta_title'), "meta_description" => config('constant.order.thankyou.meta_description')];
        return view('front-end.afterpay_order_thankyou', compact('page_content'));
    }
}
