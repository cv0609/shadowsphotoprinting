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
use Illuminate\Support\Str;

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

        $shipping_with_test_print = 0;
        $hasTestPrint = false;
        $hasRegularPrint = false;

        $shipping = $this->CartService->getShippingCharge();
        $testPrintShipping = $this->CartService->getTestPrintShippingCharge()->amount;

        foreach ($cart->items as $items) {
            if ($items->is_test_print == '1') {
                $hasTestPrint = true;
            }

            if ($items->is_test_print == '0') {
                $hasRegularPrint = true;
            }

            if ($hasTestPrint && $hasRegularPrint) {
                break;
            }
        }

        if ($hasTestPrint && $hasRegularPrint) {
            $shipping_with_test_print += $testPrintShipping + $shipping->amount;
        } elseif ($hasTestPrint) {
            $shipping_with_test_print += $testPrintShipping;
        } elseif ($hasRegularPrint) {
            $shipping_with_test_print += $shipping->amount;
        }

        $countries = Country::find(14);
        $CartTotal = $this->CartService->getCartTotal();

        $page_content = ["meta_title" => config('constant.pages_meta.checkout.meta_title'), "meta_description" => config('constant.pages_meta.checkout.meta_description')];

        return view('front-end.checkout', compact('cart', 'CartTotal', 'shipping', 'countries', 'page_content', 'user_address', 'shipping_with_test_print'));
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

        $charge = $this->stripe->chargeCustomer($customerId, $amount);

        if (isset($charge) && ($charge->status == 'succeeded' || $charge->status == 'processing' || $charge->status == 'amount_capturable_updated' || $charge->status == 'payment_failed')) {

            return $this->createOrder($charge);
        } else {
            return response()->json(['error' => true, 'message' => 'Something went wrong']);
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
            'shipping_charge' => $shipping_amount != 0 ? $shipping_amount : $shippingCharge,
            'total' => $shipping_amount != 0 ? $cart_total : $cart_total + $shippingCharge,
            'payment_id' => ($payment_method === 'stripe')
                ? ($charge->id ?? "")
                : ($afterPay->id ?? ""),
            'is_paid' => ($payment_method === 'stripe')
                ? ($charge->captured ?? '')
                : ($afterPay->status ?? ''),
            'payment_status' => ($payment_method === 'stripe')
                ? ($charge->status ?? '')
                : ($afterPay->paymentState ?? ''),
            'payment_method' => $payment_method,
            'order_status' => "0",
            'order_type' => $order_type
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
        $cart_total = $cartTotal['total'] + $shipping_charge ?? 0;
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

            $productPriceInCents = number_format($product_price * 100, 2, '.', '');

            $itemsArray[] = [
                "name" => $product_name,
                "sku" => $sku,
                "quantity" => $qty,
                "price" => [
                    // "amount" => $productPriceInCents,
                    "amount" => "0.55",
                    "currency" => "AUD"
                ]
            ];
        }

        $totalAmountInCents = number_format($cart_total * 100, 2, '.', '');
        $shippingAmountInCents = number_format($shipping_charge * 100, 2, '.', '');
        $couponDiscountInCents = number_format($coupon_discount * 100, 2, '.', '');

        // $orderDetails = [
        //     "amount" => [
        //         // "amount" => $totalAmountInCents,
        //         "amount" => "0.55",
        //         "currency" => "AUD"
        //     ],
        //     "consumer" => [
        //         "phoneNumber" => $phone,
        //         "givenNames" => $fname,
        //         "surname" => $lname,
        //         "email" => $email
        //     ],
        //     "billing" => [
        //         "name" =>  $fname . ' ' . $lname,
        //         "line1" => $street1,
        //         "line2" => $street2,
        //         "suburb" => $suburb,
        //         "state" => 'NSW',
        //         "postcode" => $postcode,
        //         "countryCode" => "AU",
        //         "phoneNumber" => $phone,
        //     ],
        //     "shipping" => [
        //         "name" => $ship_fname ? ($ship_fname . ' ' . $ship_lname) : ($fname . ' ' . $lname),
        //         "line1" => $ship_street1 ? $ship_street1 : $street1,
        //         "line2" => $ship_street2 ? $ship_street2 : $street2,
        //         "suburb" => $ship_suburb ? $ship_suburb : $suburb,
        //         "state" => 'NSW',
        //         "postcode" => $ship_postcode ? $ship_postcode : $postcode,
        //         "countryCode" => "AU",
        //         "phoneNumber" => $phone
        //     ],
        //     "courier" => [
        //         "shippedAt" => "2024-08-30",
        //         "name" => "DHL",
        //         "tracking" => "ABC123XYZ",
        //         "priority" => "STANDARD"  // Changed to a valid value
        //     ],
        //     "description" => "Order for consumer",
        //     "items" => $itemsArray,
        //     "discounts" => [
        //         [
        //             "displayName" => !empty($coupon_code) ? $coupon_code : 'Summer Discount',
        //             "amount" => [
        //                 "amount" => $couponDiscountInCents ?? "0.00",
        //                 // "amount" => "5000.00",
        //                 "currency" => "AUD"
        //             ]
        //         ]
        //     ],
        //     "merchant" => [
        //         "redirectConfirmUrl" => route('checkout.success'),
        //         "redirectCancelUrl" => route('checkout.cancel'),
        //     ],
        //     "merchantReference" => "order_reference_001",
        //     "taxAmount" => [
        //         "amount" => "0.00",
        //         "currency" => "AUD"
        //     ],
        //     "shippingAmount" => [
        //         "amount" => $shippingAmountInCents ?? "0.00",
        //         // "amount" => "5000.00",
        //         "currency" => "AUD"
        //     ]
        // ];

        // $orderDetails = [
        //     "amount" => [
        //         "amount" => "5",
        //         "currency" => "AUD"
        //     ],
        //     "consumer" => [
        //         "phoneNumber" => "0412345678",
        //         "givenNames" => "Test",
        //         "surname" => "Consumer",
        //         "email" => "test@example.com"
        //     ],
        //     "billing" => [
        //         "name" => "Test Consumer",
        //         "line1" => "123 Fake Street",
        //         "line2" => "Unit 4",
        //         "suburb" => "Realville",
        //         "state" => "NSW",
        //         "postcode" => "2000",
        //         "countryCode" => "AU",
        //         "phoneNumber" => "0412345678"
        //     ],
        //     "shipping" => [
        //         "name" => "Test Shipping Consumer",
        //         "line1" => "123 Fake Street",
        //         "line2" => "",
        //         "suburb" => "Realville",
        //         "state" => "NSW",
        //         "postcode" => "2000",
        //         "countryCode" => "AU",
        //         "phoneNumber" => "0412345678"
        //     ],
        //     "courier" => [
        //         "shippedAt" => "2024-08-30",
        //         "name" => "DHL",
        //         "tracking" => "ABC123XYZ",
        //         "priority" => "STANDARD"  // Changed to a valid value
        //     ],
        //     "description" => "Order for consumer",
        //     "items" => [
        //         [
        //             "name" => "Sample Item",
        //             "sku" => "ITEM001",
        //             "quantity" => 1,
        //             "price" => [
        //                 "amount" => "0.00",
        //                 "currency" => "AUD"
        //             ]
        //         ]
        //     ],
        //     "discounts" => [
        //         [
        //             "displayName" => "Summer Discount",
        //             "amount" => [
        //                 "amount" => "0.00",
        //                 "currency" => "AUD"
        //             ]
        //         ]
        //     ],
        //     "merchant" => [
        //         "redirectConfirmUrl" => route('checkout.success'),
        //         "redirectCancelUrl" => route('checkout.cancel'),
        //     ],
        //     "merchantReference" => "order_reference_001",
        //     "taxAmount" => [
        //         "amount" => "0.00",
        //         "currency" => "AUD"
        //     ],
        //     "shippingAmount" => [
        //         "amount" => "0.00",
        //         "currency" => "AUD"
        //     ]
        // ];

        $orderDetails = [
            "amount" => [
                "amount" => "0.55",
                "currency" => "AUD"
            ],
            "consumer" => [
                "email" => "test@example.com",
                "givenNames" => "Joe",
                "surname" => "Consumer",
                "phoneNumber" => "0400 000 000"
            ],
            "merchantReference" => "string",
            "billing" => [
                "name" => "Joe Consumer",
                "line1" => "Level 5",
                "line2" => "390 Collins Street",
                "area1" => "Melbourne",
                "region" => "VIC",
                "postcode" => "3000",
                "countryCode" => "AU",
                "phoneNumber" => "0400 000 000"
            ],
            "shipping" => [
                "name" => "Joe Consumer",
                "line1" => "Level 5",
                "line2" => "390 Collins Street",
                "area1" => "Melbourne",
                "region" => "VIC",
                "postcode" => "3000",
                "countryCode" => "AU",
                "phoneNumber" => "0400 000 000"
            ],
            "merchant" => [
                // "redirectConfirmUrl" => "https://example.com/checkout/confirm",
                // "redirectCancelUrl" => "https://example.com/checkout/cancel",
                "redirectConfirmUrl" => route('checkout.success'),
                "redirectCancelUrl" => route('checkout.cancel'),
                "popupOriginUrl" => "https://merchant.com/cart",
                "name" => "string"
            ],
            "items" => [
                [
                    "name" => "Blue Carabiner",
                    "sku" => "12341234",
                    "quantity" => 1,
                    "pageUrl" => "https://merchant.example.com/carabiner-354193.html",
                    "imageUrl" => "https://merchant.example.com/carabiner-7378-391453-1.jpg",
                    "price" => [
                        "amount" => "0.55",
                        "currency" => "AUD"
                    ],
                    "categories" => [
                        [
                            "Sporting Goods",
                            "Climbing Equipment",
                            "Climbing",
                            "Climbing Carabiners"
                        ],
                        [
                            "Sale",
                            "Climbing"
                        ]
                    ],
                    "estimatedShipmentDate" => "2023-08-01"
                ]
            ],
            "courier" => [
                "shippedAt" => "2023-08-24T14:15:22Z",
                "name" => "Australia Post",
                "tracking" => "AA0000000000000",
                "priority" => "STANDARD"
            ],
            "taxAmount" => [
                "amount" => "0.00",
                "currency" => "AUD"
            ],
            "shippingAmount" => [
                "amount" => "0.00",
                "currency" => "AUD"
            ],
            "discounts" => [
                [
                    "displayName" => "New Customer Coupon",
                    "amount" => [
                        "amount" => "0.00",
                        "currency" => "AUD"
                    ]
                ]
            ],
            "description" => "string"
        ];
        

        $response = $this->AfterPayService->charge($orderDetails);
        \Log::info($response);

        if (isset($response['redirectCheckoutUrl']) && !empty($response['redirectCheckoutUrl'])) {
            $token = $response['token'];
            \Log::info($token);
            \Log::info('token');
            Session::put('afterpay_token', $token);
            return response()->json(['error' => false, 'data' => $response['redirectCheckoutUrl']]);
        }

        $log = new AfterPayLogs;
        $log->logs = json_encode($response) ?? '';
        $log->save();

        return response()->json(['error' => true, 'data' => $response['error'] ?? 'Error processing Afterpay payment.']);
    }

    public function afterpaySuccess()
    {
        $token = Session::get('afterpay_token');
        \Log::info($token);
        \Log::info('afterpay_token success');

        if (!$token) {
            return redirect()->route('checkout')->with('error', 'Session expired or invalid.');
        }

        $afterPay = $this->AfterPayService->validateAfterpayOrder($token);
        \Log::info($afterPay);
        \Log::info('afterpay_logs success');

        if (isset($afterPay) && !empty($afterPay)) {
            $log = new AfterPayLogs;
            $log->logs = json_encode($afterPay) ?? '';
            $log->save();
        }

        if (isset($afterPay['status'])) {

            $this->createOrder($charge = null, $afterPay = null);

            Session::forget(['order_address', 'coupon', 'billing_details', 'afterpay_token', 'order_type']);
            return redirect()->route('order.success');
        } else {
            return redirect()->route('checkout')->with('error', 'Payment failed or was canceled.');
        }
    }

    public function afterpayCancel()
    {
        \Log::info('cancel pay');
        Session::forget(['order_address', 'coupon', 'billing_details', 'afterpay_token', 'order_type']);
        return redirect()->route('checkout')->with('error', 'Payment was cancelled. Please try again.');
    }

    public function orderSuccess()
    {
        $page_content = ["meta_title" => config('constant.order.thankyou.meta_title'), "meta_description" => config('constant.order.thankyou.meta_description')];
        return view('front-end.afterpay_order_thankyou', compact('page_content'));
    }
}
