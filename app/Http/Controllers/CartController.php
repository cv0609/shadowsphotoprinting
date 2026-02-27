<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartData;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Affiliate;
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
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class CartController extends Controller
{
    protected $CartService;
    public function __construct(CartService $CartService)
    {
        $this->CartService = $CartService;
    }

    public function applyReferralCouponIfNeeded()
    {
      
        if (Auth::check() && !empty(Auth::user())) {
            $user = Auth::user();
            $auth_id = $user->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
            // If no referral code in session, check if user is referred and has no orders
            if (!Session::has('referral_code') && $user->referred_by && $user->orders()->count() === 0 && $cart && $cart->items->count() > 0) {
                $affiliate = Affiliate::find($user->referred_by);
                if ($affiliate && $affiliate->coupon_code) {
                    $coupon = Coupon::where('code', $affiliate->coupon_code)->where('is_active', true)->first();
    
                    if ($coupon) {
                        $total = $this->CartService->getCartTotal();
                        $amount = ($coupon->amount / 100) * $total['subtotal'];
    
                        Session::put('referral_code', $affiliate->referral_code); // store for future logic
                        Session::put('coupon', [
                            'code' => $coupon->code,
                            'discount_amount' => $amount,
                        ]);
                    }
                }
            }
    
        } else {
            // Guest user logic with referral_code already in session
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
    
            if (Session::has('referral_code') && $cart && $cart->items->count() > 0) {
                $referralCode = Session::get('referral_code');
                $affiliate = Affiliate::where('referral_code', $referralCode)->first();
    
                if ($affiliate && $affiliate->coupon_code) {
                    $coupon = Coupon::where('code', $affiliate->coupon_code)->where('is_active', true)->first();
    
                    if ($coupon) {
                        $total = $this->CartService->getCartTotal();
                        $amount = ($coupon->amount / 100) * $total['subtotal'];
    
                        Session::put('coupon', [
                            'code' => $coupon->code,
                            'discount_amount' => $amount,
                        ]);
                    }
                }
            }
        }
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

                $is_package = isset($cart_item['is_package']) ? $cart_item['is_package'] : 0;
                $package_price = isset($cart_item['package_price']) ? $cart_item['package_price'] : null;
                $package_product_id = isset($cart_item['package_product_id']) ? $cart_item['package_product_id'] : null;
                
                $testPrint = '0';
                $packageItemCount = 0; // Track how many package items we've added for this package

                foreach ($request->selectedImages as $selectedImage) {

                    $ImagePath = $selectedImage;
                    $wtrelativeImagePath = '';

                    if (isset($cart_item['testPrint']) && !empty($cart_item['testPrint'])) {
                        $testPrint = $cart_item['testPrint'];
                        $testPrintPrice = $cart_item['testPrintPrice'];
                        $testPrintQty = $cart_item['testPrintQty'];
                        $testPrintCatId = $cart_item['testPrintCategory_id'];

                        $testPrintData = TestPrint::where('category_id',$testPrintCatId)->first();

                        if($cart_item['quantity'] < $testPrintData->min_qty || $cart_item['quantity'] > (int)$testPrintData->qty) {
                            return response()->json([
                                'error' => true,
                                'message' => 'The quantity must be greater than 1 and less than or equal to ' . $testPrintData->qty . '.'
                            ]);
                        }
                        $wtrelativeImagePath = $this->CartService->addWaterMark($ImagePath);
                    }
                    
                    // For package products, check if this package already exists in cart
                    $packageAlreadyInCart = false;
                    if ($is_package && $package_product_id) {
                        $packageAlreadyInCart = CartData::where('cart_id', $cartId)
                            ->where('package_product_id', $package_product_id)
                            ->exists();
                    }
                    
                    // For package products, only set package_price for the first item from this package
                    $finalPackagePrice = $package_price;
                    if ($is_package && $package_price) {
                        if (!$packageAlreadyInCart && $packageItemCount == 0) {
                            // First item of this package gets the package price
                            $finalPackagePrice = $package_price;
                            $packageItemCount++;
                        } else {
                            // Subsequent items get null package price
                            $finalPackagePrice = null;
                        }
                    }

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
                        "is_package" => $is_package,
                        "package_price" => $finalPackagePrice,
                        "package_product_id" => $package_product_id
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

        $this->applyReferralCouponIfNeeded();

        // Recalculate shipping after adding items to cart
        $this->recalculateShippingAfterCartUpdate();

        return response()->json(['error' => false, 'message' => 'Cart updated', 'count' => $cartCount]);
    }

    public function orderType(Request $request){
       $order_type = $request['order_type'];
       Session::put('order_type',$order_type);
       return response()->json(['order_type' => $request->order_type]);
    }


    public function shutterPoint(Request $request){
        $shutter_point = $request['shutter_point'];
        Session::put('shutter_point',$shutter_point);
       return response()->json(['order_type' => Session::get('order_type')]);
    }


    public function cart()
    {
        $affiliate_sales = null;

        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->where('session_id' , null)->with(['items' => function($query) {
                // Order items so package items come first, then regular items
                // Within package items, order by package_product_id, then by creation time
                $query->orderByRaw('CASE WHEN is_package = 1 THEN 0 ELSE 1 END, package_product_id ASC, created_at ASC');
            }, 'items.product'])->first();
            
            if(Auth::user()->role === 'affiliate'){
                $affiliate_id = Auth::user()->affiliate->id;
                $affiliate_sales = \App\Models\AffiliateSale::getTotalsForAffiliate($affiliate_id);

                if(Session::has('shutter_point')){
                    $cart->update(['shutter_point'=>Session::get('shutter_point')]);
                }
            }

        } else {
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with(['items' => function($query) {
                // Order items so package items come first, then regular items
                // Within package items, order by package_product_id, then by creation time
                $query->orderByRaw('CASE WHEN is_package = 1 THEN 0 ELSE 1 END, package_product_id ASC, created_at ASC');
            }, 'items.product'])->first();
        }

        $session_id = Session::getId();

        $countries = Country::with('states')->find(14);
        
        $this->applyReferralCouponIfNeeded();

        $CartTotal = $this->CartService->getCartTotal();

        $shipping = $this->CartService->getShippingCharge();
        
        $page_content = ["meta_title" => config('constant.pages_meta.cart.meta_title'), "meta_description" => config('constant.pages_meta.cart.meta_description')];

        if (!empty($cart)) {
            return view('front-end.cart', compact('cart', 'CartTotal', 'shipping', 'affiliate_sales', 'countries', 'page_content'));
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

                $this->applyReferralCouponIfNeeded();

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

        // Recalculate shipping after item removal
        $this->recalculateShippingAfterCartUpdate();

        return redirect()->route('cart')->with('success', 'Item removed from cart');
    }



    public function applyCoupon(Request $request)
    {
        $couponCodeInput = $request->coupon_code;
        $code = is_array($couponCodeInput) ? ($couponCodeInput['code'] ?? '') : $couponCodeInput;
        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();

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

        if($coupon->is_gift_card != '1'){

            if ($total['subtotal'] < $coupon->minimum_cart_total) {
                return ['success' => false, 'message' => 'Cart total is less than the minimum required to apply this coupon'];
            }

            if ($coupon->auto_applied != '1') {
                if ($total['subtotal'] < $coupon->minimum_spend || $total['subtotal'] > $coupon->maximum_spend) {
                    return ['success' => false, 'message' => 'you can use this coupon between ' . $coupon->minimum_spend . ' To ' . $coupon->maximum_spend . ' amount'];
                }
            }
            // if ($coupon->use_limit && $coupon->used >= $coupon->use_limit) {
            //     return ['success' => false, 'message' => 'This coupon has reached its usage limit.'];
            // }
        }

        if (isset($coupon->product_category) && !empty($coupon->product_category) && $coupon->product_category != null) {

            $couponCategories = explode(',', $coupon->product_category);

            $couponCategories = array_map('strval', $couponCategories);

            foreach ($cart->items as $item) {

                // Handle gift card items - they have category_id = 8
                if ($item->product_type == 'gift_card') {
                    $productCategory = '6'; // Gift card category ID
                } elseif ($item->product && $item->product->category_id) {
                    $productCategory = (string)$item->product->category_id;
                } else {
                    // Skip items without valid category
                    continue;
                }

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

                // Skip gift card items in product-specific validation as they use GiftCardCategory, not Product
                // Gift card coupons should use product_category instead
                if ($item->product_type == 'gift_card') {
                    continue;
                }

                if (!$item->product) {
                    continue;
                }

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

        // For gift card coupons (is_gift_card = 1), ensure cart contains gift card items
        // Note: If product_category is set, the category validation above already handles the check
        if ($coupon->is_gift_card == '1') {
            $hasGiftCardItem = false;
            foreach ($cart->items as $item) {
                if ($item->product_type == 'gift_card') {
                    $hasGiftCardItem = true;
                    break;
                }
            }
            
            if (!$hasGiftCardItem) {
                return ['success' => false, 'message' => 'This coupon is only applicable to gift card items in your cart'];
            }
        }

        if ($coupon->use_limit !== null && $coupon->use_limit <= 0) {
            return ['success' => false, 'message' => 'Your coupon limit has expired.'];
        }

        $amount = 0;

        // DOUBLE15 only: canvas prints – 15% off entire subtotal. All other coupons use the logic below (gift card / type 0 / type 1).
        if (strtoupper((string) $coupon->code) === 'DOUBLE15' && isset($coupon->product_category) && $coupon->product_category != '') {
            $canvasCategoryIds = ProductCategory::where('name', 'like', '%canvas%')->pluck('id')->map(function ($id) {
                return (int) $id;
            })->toArray();
            $couponCatIds = array_map('intval', explode(',', $coupon->product_category));
            $isCanvasCoupon = !empty($canvasCategoryIds) && array_intersect($couponCatIds, $canvasCategoryIds);

            if ($isCanvasCoupon) {
                $canvasCount = 0;
                foreach ($cart->items as $item) {
                    if (in_array($item->product_type, ['gift_card', 'photo_for_sale', 'hand_craft'])) {
                        continue;
                    }
                    if (isset($item->is_package) && (string) $item->is_package === '1') {
                        continue;
                    }
                    if (isset($item->is_test_print) && (string) $item->is_test_print === '1') {
                        continue;
                    }
                    if (!$item->product || !in_array((int) $item->product->category_id, $canvasCategoryIds)) {
                        continue;
                    }
                    if ((int) $item->quantity <= 0) {
                        continue;
                    }
                    $canvasCount++;
                }

                if ($canvasCount < 2) {
                    return ['success' => false, 'message' => 'Add at least 2 canvas products (mix and match any size) to use this coupon.'];
                }

                // 15% off entire subtotal (cart already validated as canvas-only for this coupon)
                $amount = round($total['subtotal'] * 0.15, 2);
                $amount = min($amount, $total['subtotal']);
            }
        }

        // Handle gift card coupons separately - they use the amount directly
        if ($amount == 0 && $coupon->is_gift_card == '1') {
            if ($coupon->amount <= 0) {
                return ['success' => false, 'message' => 'Expired Gift card voucher.'];
            }
            $amount = $coupon->amount;
        } elseif ($amount == 0) {
            // Regular coupon calculation based on type
            if ($coupon->type == "0") {
                $amount = $coupon->amount;
            } elseif ($coupon->type == "1") {
                $amount = ($coupon->amount / 100) * $total['subtotal'];
            }
        }

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

        $this->applyReferralCouponIfNeeded();

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

        // if (!Session::has('coupon')) {

        foreach ($cart->items as $item) {
            $productCategoryId = (string)$item->product->category_id;
            $productId = (string)$item->product_id;
            $this->CartService->autoAppliedCoupon($productId, $productCategoryId, $cartCount);
            // Check if a coupon was applied and terminate the loop if it was
            if (Session::has('coupon')) {
                break; // Exit the loop if a coupon is applied
            }
        }
        // }

        // Recalculate shipping after cart update
        $this->recalculateShippingAfterCartUpdate();
        
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

    /**
     * Recalculate shipping after cart updates
     */
    private function recalculateShippingAfterCartUpdate()
    {
        try {
            $auth_id = Auth::check() && !empty(Auth::user()) ? Auth::user()->id : null;
            $session_id = $auth_id ? null : Session::getId();

            if ($auth_id && !empty($auth_id)) {
                $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
            } else {
                $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
            }

            if ($cart && !$cart->items->isEmpty()) {
                // Prepare cart items for shipping calculation
                $cartItems = [];
                foreach ($cart->items as $item) {
                    $cartItems[] = [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'product_type' => $item->product_type,
                        'is_test_print' => $item->is_test_print // Ensure this is passed
                    ];
                }

                // Calculate shipping and update session
                $shippingService = new \App\Services\CartShippingService();
                $shippingOptions = $shippingService->calculateShipping($cartItems);

                if (!empty($shippingOptions)) {
                    // Auto-select the best shipping option
                    $selectedShipping = $this->autoSelectShippingOption($shippingOptions);
                    
                    if ($selectedShipping) {
                        // Save to session
                        session([
                            'selected_shipping' => [
                                'carrier' => $selectedShipping['carrier'],
                                'service' => $selectedShipping['service'],
                                'price' => (float) $selectedShipping['price']
                            ]
                        ]);
                    }
                } else {
                    // Clear session shipping if no options are available
                    session()->forget('selected_shipping');
                    Log::info('No shipping options available, cleared session shipping in cart update');
                }
            } else {
                // Clear shipping if cart is empty
                session()->forget('selected_shipping');
            }
        } catch (\Exception $e) {
            // Log error but don't break the cart update
            Log::error('Error recalculating shipping after cart update: ' . $e->getMessage());
        }
    }

    /**
     * Auto-select the best shipping option
     */
    private function autoSelectShippingOption($shippingOptions)
    {
        // Check if there's already a session shipping selection
        $existingShipping = session('selected_shipping');
        
        if ($existingShipping) {
            // Try to find the existing selection in current options
            foreach ($shippingOptions as $option) {
                if ($option['carrier'] === $existingShipping['carrier'] && 
                    $option['service'] === $existingShipping['service']) {
                    return $option;
                }
            }
        }
        
        // Auto-selection logic: prioritize Australia Post snail_mail
        foreach ($shippingOptions as $option) {
            if ($option['carrier'] === 'auspost' && $option['service'] === 'snail_mail') {
                return $option;
            }
        }
        
        // Fallback to first option
        return $shippingOptions[0] ?? null;
    }

    /**
     * Get updated cart total from session
     */
    public function getUpdatedTotal()
    {
        try {
            // Get the current cart total with shipping from session
            $cartTotal = $this->CartService->getCartTotal();
            
            Log::info('Updated cart total requested:', [
                'total' => $cartTotal['total'],
                'subtotal' => $cartTotal['subtotal'],
                'shipping_charge' => $cartTotal['shippingCharge'],
                'session_shipping' => session('selected_shipping')
            ]);
            
            return response()->json([
                'success' => true,
                'total' => $cartTotal['total'],
                'subtotal' => $cartTotal['subtotal'],
                'shipping_charge' => $cartTotal['shippingCharge']
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting updated cart total: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting updated cart total'
            ]);
        }
    }
}
