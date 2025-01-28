<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartData;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Shipping;
use App\Models\State;
use App\Models\Product;
use App\Models\GiftCardCategory;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\CartService;
use Illuminate\Support\Facades\Session;
use App\Mail\MakeOrder;
use App\Models\TestPrint;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class CartController extends Controller
{
    protected $CartService;
    public function __construct(CartService $CartService)
    {
        $this->CartService = $CartService;
    }

    public function addToCart(Request $request)
    {
        if (Session::has('coupon')) {
            $request_data = request()->merge(['coupon_code' => Session::get('coupon')]);
            $response = $this->applyCoupon($request_data);
            if ($response['success'] === false) {
                Session::forget('coupon');
            }
        }

        if ($request->item_type == 'hand_craft') {
            $slug = 'hand-craft';
            $hand_craft_cat = $this->CartService->getProductStock($slug, $request->cart_items[0]['product_id']);

            if ($hand_craft_cat) {
                $quantity = $request->cart_items[0]['quantity'];
                $stock_qty = $hand_craft_cat->getProductStock->qty;

                if ($stock_qty <  $quantity) {
                    return response()->json(['error' => true, 'message' => 'Maximum quantity limited to 1.']);
                }
            }
        }

        $auth_id = Auth::check() && !empty(Auth::user()) ? Auth::user()->id : null;
        $session_id = $auth_id ? null : Session::getId();

        $cart = Cart::firstOrCreate([
            "user_id" => $auth_id,
            "coupon_id" => null,
            "session_id" => $session_id
        ]);

        if ($cart) {

            $cartId = $cart->id;
            $itemType = $request->item_type ?? '';

            if ($itemType == 'hand_craft') {

                if ($hand_craft_cat) {

                    $cartData = CartData::firstWhere([
                        ['product_type', 'hand_craft'],
                        ['product_id', $request->cart_items[0]['product_id']],
                        ['cart_id', $cartId]
                    ]);

                    if ($cartData) {

                        if ($stock_qty <  $cartData->quantity + $request->cart_items[0]['quantity']) {
                            return response()->json(['error' => true, 'message' => 'Maximum quantity limited to 1']);
                        }
                    }
                }
            }

            $giftcard = $itemType == 'gift_card' ? [
                'from' => $request->from,
                'giftcard_msg' => $request->giftcard_msg,
                'reciept_email' => $request->reciept_email
            ] : [];

            $photoForSale = $itemType == 'photo_for_sale' ? [
                'photo_for_sale_size' => $request->photo_for_sale_size,
                'photo_for_sale_type' => $request->photo_for_sale_type,
                'product_min_max_price' => $request->product_min_max_price
            ] : [];


            foreach ($request->cart_items as $cart_item) {
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];

                $testPrint = '0';


                foreach ($request->selectedImages as $selectedImage) {

                    // $tempFileName = basename($selectedImage);
                    // $tempImagePath = 'public/temp/' . $tempFileName;
                    // $permanentImagePath = 'public/assets/images/order_images/'.$tempFileName;
                    // Storage::move($tempImagePath, $permanentImagePath);
                    // $ImagePath = 'storage/assets/images/order_images/' . $tempFileName;
                    $ImagePath = $selectedImage;

                    // $wtimagePath = public_path('storage/assets/images/order_images/' . $tempFileName);

                    // if (!file_exists($wtimagePath)) {
                    //     return response()->json(['error' => 'Image not found: ' . $tempFileName], 404);
                    // }

                    // if (isset($cart_item['testPrint']) && !empty($cart_item['testPrint'])) {

                    //     $testPrint = $cart_item['testPrint'];
                    //     $testPrintPrice = $cart_item['testPrintPrice'];
                    //     $testPrintQty = $cart_item['testPrintQty'];
                    //     $testPrintCatId = $cart_item['testPrintCategory_id'];

                    //     $testPrintData = TestPrint::where('category_id',$testPrintCatId)->first();

                    //     if($cart_item['quantity'] < $testPrintData->min_qty || $cart_item['quantity'] > (int)$testPrintData->qty) {
                    //         return response()->json([
                    //             'error' => true,
                    //             'message' => 'The quantity must be greater than 1 and less than or equal to ' . $testPrintData->qty . '.'
                    //         ]);
                    //     }

                    //     $wtrelativeImagePath = $this->CartService->addWaterMark($wtimagePath,$tempFileName);
                    // }

                    $insertData = [
                        "cart_id" => $cartId,
                        "product_id" => $product_id,
                        "quantity" => $quantity,
                        "selected_images" => $ImagePath,
                        "product_type" => $itemType,
                        "product_price" => $request->card_price ?? 0,
                        "is_test_print" => isset($testPrint) && ($testPrint == '1') ? '1' : '0',
                        "test_print_price" => isset($testPrintPrice) && !empty($testPrintPrice) ? $testPrintPrice  : 0.00,
                        "test_print_qty" => isset($testPrintQty) && !empty($testPrintQty) ? $testPrintQty  : '',
                        "watermark_image" => isset($testPrintQty) && !empty($testPrintQty) ? $wtrelativeImagePath  : '',
                        "test_print_cat" => isset($testPrintQty) && !empty($testPrintQty) ? $testPrintCatId : '',
                    ];

                    if ($itemType == 'gift_card') {
                        $insertData = array_merge($insertData, [
                            'product_desc' => json_encode($giftcard),
                            'product_type' => $itemType,
                            'product_price' => $request->card_price
                        ]);
                    }

                    if ($itemType == 'photo_for_sale') {
                        $insertData = array_merge($insertData, [
                            'product_desc' => json_encode($photoForSale),
                            'product_type' => $itemType,
                            'product_price' => $request->card_price
                        ]);
                    }

                    if ($itemType == 'hand_craft') {
                        $insertData = array_merge($insertData, [
                            'product_type' => $itemType,
                            'product_price' => $request->card_price
                        ]);
                    }

                    $existingCartItem = CartData::where('cart_id', $cartId)
                        ->where('product_id', $product_id)
                        ->where('selected_images', $ImagePath)
                        ->first();

                    if ($existingCartItem) {
                        $existingCartItem->quantity += $quantity;
                        if ($itemType == 'gift_card') {
                            $existingCartItem->product_desc = json_encode($giftcard) ?? '';
                            $existingCartItem->product_type = $itemType ?? '';
                            $existingCartItem->product_price = $request->card_price ?? '';
                        }

                        if ($itemType == 'photo_for_sale') {
                            $existingCartItem->product_desc = json_encode($photoForSale) ?? '';
                            $existingCartItem->product_type = $itemType ?? '';
                            $existingCartItem->product_price = $request->card_price ?? '';
                        }

                        if ($itemType == 'hand_craft') {
                            $existingCartItem->product_type = $itemType ?? '';
                            $existingCartItem->product_price = $request->card_price ?? '';
                        }

                        $existingCartItem->save();
                    } else {
                        CartData::create($insertData);
                    }
                }
            }
        }

        if ($auth_id && !empty($auth_id)) {
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        } else {
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        $cartCount = CartData::where('cart_id', $cartId)->sum('quantity');
        isset($cartCount) && !empty($cartCount) ? $cartCount : 0;


        if (!Session::has('coupon')) {

            foreach ($cart->items as $item) {
                $productCategoryId = (string)$item->product->category_id;
                $productId = (string)$item->product_id;
                $this->CartService->autoAppliedCoupon($productId, $productCategoryId, $cartCount);
                // Check if a coupon was applied and terminate the loop if it was
                if (Session::has('coupon')) {
                    break; // Exit the loop if a coupon is applied
                }
            }
        }

        // Auto Applied sail

        // $currentDate = Carbon::now()->format('Y-m-d'); 

        // $coupon = Coupon::where('is_active', '1')
        //     ->where('auto_applied', '1')
        //     ->where('start_date', '<=', $currentDate) // Check if coupon has started
        //     ->where('end_date', '>=', $currentDate)   // Check if coupon hasn't expired
        //     ->first();

        // if(isset($coupon) && !empty($coupon)){
        //     $request_data = request()->merge(['coupon_code' => $coupon->code]);
        //     $response = $this->applyCoupon($request_data);

        //     if($response['success'] === false)
        //     {
        //       Session::forget('coupon');
        //     }
        // }

        return response()->json(['error' => false, 'message' => 'Cart updated', 'count' => $cartCount]);
    }

    public function cart()
    {
        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        } else {
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        $session_id = Session::getId();

        $countries = Country::with('states')->find(14);

        $CartTotal = $this->CartService->getCartTotal();
        // $shipping = $this->CartService->getShippingCharge();


        $shipping_with_test_print = 0;
        $hasTestPrint = false;
        $hasRegularPrint = false;

        $shipping = $this->CartService->getShippingCharge();
        $testPrintShipping = $this->CartService->getTestPrintShippingCharge()->amount;

        foreach ($cart->items as $items) {
            if ($items->is_test_print == '1') {
                $hasTestPrint = true;  // Flag that the cart has test print shipping
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

        $page_content = ["meta_title" => config('constant.pages_meta.cart.meta_title'), "meta_description" => config('constant.pages_meta.cart.meta_description')];

        if (!empty($cart)) {
            return view('front-end.cart', compact('cart', 'CartTotal', 'shipping', 'countries', 'page_content', 'shipping_with_test_print'));
        } else {

            return redirect('shop');
        }
    }

    public function removeFromCart($product_id)
    {
        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        } else {
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        if ($cart) {
            $CartData = CartData::where('cart_id', $cart->id)
                ->where('id', $product_id)
                ->delete();
        }

        if (!$cart->items->isEmpty()) {
            if (Session::has('coupon')) {
                $request_data = request()->merge(['coupon_code' => Session::get('coupon')]);
                $response = $this->applyCoupon($request_data);
                if ($response['success'] === false) {
                    Session::forget('coupon');
                }
            }

            if (!Session::has('coupon')) {

                $auth_id = Auth::check() && !empty(Auth::user()) ? Auth::user()->id : null;
                $session_id = $auth_id ? null : Session::getId();

                if ($auth_id && !empty($auth_id)) {
                    $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
                } else {
                    $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
                }

                $cartId = '';
                if (isset($cart) && !empty($cart)) {
                    $cartId = $cart->id;
                }

                $cartCount = CartData::where('cart_id', $cartId)->sum('quantity');
                isset($cartCount) && !empty($cartCount) ? $cartCount : 0;

                if (!Session::has('coupon')) {

                    foreach ($cart->items as $item) {
                        $productCategoryId = (string)$item->product->category_id;
                        $productId = (string)$item->product_id;
                        $this->CartService->autoAppliedCoupon($productId, $productCategoryId, $cartCount);
                        // Check if a coupon was applied and terminate the loop if it was
                        if (Session::has('coupon')) {
                            break; // Exit the loop if a coupon is applied
                        }
                    }
                }
            }
        }

        // Check if the cart is empty and forget the coupon if necessary
        if ($cart->items->isEmpty()) {
            Session::forget('coupon');
        }

        return redirect()->route('cart')->with('success', 'Item removed from cart');
    }

    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->coupon_code)->where('is_active', true)->first();

        $total = $this->CartService->getCartTotal();

        $cart = [];

        if (empty($coupon) && !isset($coupon)) {
            return ['success' => false, 'message' => 'Coupon is not valid.'];
        }

        if (!$coupon) {
            return ['success' => false, 'message' => 'Coupon does not exist'];
        }

        $currentDate = date('Y-m-d');

        if ($coupon->start_date > $currentDate) {
            return ['success' => false, 'message' => 'Coupon is not yet valid'];
        }

        if ($coupon->end_date < $currentDate) {
            return ['success' => false, 'message' => 'Coupon has expired'];
        }

        // if (!$coupon->isStarted()) {
        //     return ['success' => false, 'message' => 'Coupon is not yet valid'];
        // }

        // if ($coupon->isExpired()) {
        //     return ['success' => false, 'message' => 'Coupon has expired'];
        // }

        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        } else {
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        if (!$cart) {
            return ['success' => false, 'message' => 'Cart is empty'];
        }

        if ($total['subtotal'] < $coupon->minimum_cart_total) {
            return ['success' => false, 'message' => 'Cart total is less than the minimum required to apply this coupon'];
        }

        if ($coupon->auto_applied != '1') {
            if ($total['subtotal'] < $coupon->minimum_spend || $total['subtotal'] > $coupon->maximum_spend) {
                return ['success' => false, 'message' => 'you can use this coupon between ' . $coupon->minimum_spend . ' To ' . $coupon->maximum_spend . ' amount'];
            }
        }


        if ($coupon->use_limit && $coupon->used >= $coupon->use_limit) {
            return ['success' => false, 'message' => 'This coupon has reached its usage limit.'];
        }

        if (isset($coupon->product_category) && !empty($coupon->product_category) && $coupon->product_category != null) {
            // $couponCategories = explode(',', $coupon->product_category);
            // foreach ($cart->items as $item) {
            //     $productCategory = $item->product->category_id;
            //     // $productCategories = $item->product->category_id->pluck('id')->toArray();
            //     if (!in_array($productCategory, $couponCategories)) {
            //         return ['success' => false, 'message' => 'This coupon is not applicable to the items in your cart'];
            //     }
            //     if (!in_array($productCategories, $couponCategories)) {
            //         return ['success' => false, 'message' => 'This coupon is not applicable to the items in your cart'];
            //     }
            // }

            $couponCategories = explode(',', $coupon->product_category);

            $couponCategories = array_map('strval', $couponCategories);

            foreach ($cart->items as $item) {

                $productCategory = (string)$item->product->category_id;

                if (!in_array((string)$productCategory, $couponCategories)) {
                    return ['success' => false, 'message' => 'This coupon is not applicable to the items in your cart'];
                }

                if (in_array((string)$productCategory, $couponCategories)) {
                    if (isset($coupon->qty) && !empty($coupon->qty) && $coupon->qty > 0) {
                        if ((int)$item->quantity < (int)$coupon->qty) {
                            return ['success' => false, 'message' => 'Quantity in the cart is less than the coupon required ' . $coupon->qty . ' quantity.'];
                        }
                    }
                }
            }
        }

        if (isset($coupon->products) && !empty($coupon->products) && $coupon->products != null) {
            $couponProducts = explode(',', $coupon->products);
            foreach ($cart->items as $item) {

                if (!in_array($item->product->id, $couponProducts)) {
                    return ['success' => false, 'message' => 'This coupon is not applicable to the items in your cart based on product'];
                }

                if (in_array($item->product->id, $couponProducts)) {
                    if (isset($coupon->qty) && !empty($coupon->qty) && $coupon->qty > 0) {
                        if ((int)$item->quantity < (int)$coupon->qty) {
                            return ['success' => false, 'message' => 'Quantity in the cart is less than the coupon required ' . $coupon->qty . ' quantity.'];
                        }
                    }
                }
            }
        }

        if (!isset($coupon->products) && empty($coupon->products) && !isset($coupon->product_category) && empty($coupon->product_category)) {

            foreach ($cart->items as $item) {
                if (isset($coupon->qty) && !empty($coupon->qty) && $coupon->qty > 0) {
                    if ((int)$item->quantity < (int)$coupon->qty) {
                        return ['success' => false, 'message' => 'Quantity in the cart is less than the coupon required ' . $coupon->qty . ' quantity.'];
                    }
                }
            }
        }

        $amount = 0;

        if ($coupon->type == "0") {
            $amount = $coupon->amount;
        } elseif ($coupon->type == "1") {
            $amount = ($coupon->amount / 100) * $total['subtotal'];
        }
        $coupon->used++;
        $coupon->save();

        Session::put('coupon', [
            'code' => $coupon->code,
            'discount_amount' => $amount,
        ]);

        return ['success' => true, 'total' => $total['subtotal'] - $amount];
    }

    public function resetCoupon()
    {
        Session::forget('coupon');
        return back()->with('success', 'Coupon code has been reset successfully.');
    }

    public function billingDetails(Request $request)
    {

        $state_name = State::whereId($request->state)->select('name')->first();
        $session_data = ['country' => $request->country, 'state' => $state_name, 'state_id' => $request->state, 'city' => $request->city, 'postcode' => $request->postcode];
        Session::put('billing_details', $session_data);
        return  redirect('cart');
    }

    public function updateCart(Request $request)
    {
        foreach ($request->data as $data) {
            if ($data['product_type'] == 'hand_craft') {
                $slug = 'hand-craft';
                $hand_craft_cat = $this->CartService->getProductStock($slug, $data['product_id']);

                if ($hand_craft_cat) {
                    $quantity = $data['quantity'];
                    $stock_qty = $hand_craft_cat->getProductStock->qty;
                    if ($stock_qty <  $quantity) {
                        return response()->json(['error' => true, 'message' => 'Product out of stock.']);
                    }
                }
            }

            if (isset($data['is_test_print']) && $data['is_test_print'] == '1') {

                $is_test_print_cat = $data['is_test_print'];

                $testPrintData = TestPrint::where('category_id', $is_test_print_cat)->first();

                if ((int)$data['quantity'] < $testPrintData->min_qty || (int)$data['quantity'] > (int)$testPrintData->qty) {
                    return response()->json([
                        'error' => true,
                        'message' => 'The quantity must be greater than 1 and less than or equal to ' . $testPrintData->qty . '.'
                    ]);
                }
            }

            CartData::whereId($data['rowId'])->update(['quantity' => $data['quantity']]);
        }

        if (Session::has('coupon')) {
            $request_data = request()->merge(['coupon_code' => Session::get('coupon')]);
            $response = $this->applyCoupon($request_data);
            if ($response['success'] === false) {
                Session::forget('coupon');
            }
        }

        $auth_id = Auth::check() && !empty(Auth::user()) ? Auth::user()->id : null;
        $session_id = $auth_id ? null : Session::getId();

        if ($auth_id && !empty($auth_id)) {
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        } else {
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        $cartId = '';
        if (isset($cart) && !empty($cart)) {
            $cartId = $cart->id;
        }

        $cartCount = CartData::where('cart_id', $cartId)->sum('quantity');
        isset($cartCount) && !empty($cartCount) ? $cartCount : 0;

        if (!Session::has('coupon')) {

            foreach ($cart->items as $item) {
                $productCategoryId = (string)$item->product->category_id;
                $productId = (string)$item->product_id;
                $this->CartService->autoAppliedCoupon($productId, $productCategoryId, $cartCount);
                // Check if a coupon was applied and terminate the loop if it was
                if (Session::has('coupon')) {
                    break; // Exit the loop if a coupon is applied
                }
            }
        }

        session()->flash('success', 'Cart updated successfully.');
    }

    public function getCartCount(Request $request)
    {
        $session_id = Session::getId();

        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items')->first();
        } else {
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items')->first();
        }

        // Fetch the cart with items

        // Calculate the total count of items in the cart
        $itemCount = 0;
        if ($cart) {
            $itemCount = $cart->items->sum('quantity');
        }

        return $itemCount;
    }
}
