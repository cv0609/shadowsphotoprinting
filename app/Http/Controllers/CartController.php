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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\CartService;
use Illuminate\Support\Facades\Session;
use App\Mail\MakeOrder;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    protected $CartService;
    public function __construct(CartService $CartService)
    {
        $this->CartService = $CartService;
    }

    public function addToCart(Request $request)
    {
        if(Session::has('coupon'))
        {
            $request_data = request()->merge(['coupon_code' => Session::get('coupon')]);
            $response = $this->applyCoupon($request_data);
            if($response['success'] === false)
            {
            Session::forget('coupon');
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

                foreach ($request->selectedImages  as $selectedImage) {
                    $tempFileName = basename($selectedImage);
                    $tempImagePath = 'public/temp/' . $tempFileName;
                    $permanentImagePath = 'public/assets/images/order_images/'.$tempFileName;
                    Storage::move($tempImagePath, $permanentImagePath);
                    $ImagePath = 'storage/assets/images/order_images/' . $tempFileName;

                    $insertData = [
                        "cart_id" => $cartId,
                        "product_id" => $product_id,
                        "quantity" => $quantity,
                        "selected_images" => $ImagePath,
                        "product_type" => $itemType,
                        "product_price" => $request->card_price ?? 0
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

                        $existingCartItem->save();
                    } else {
                       CartData::create($insertData);
                    }
                }
            }
        }


        if(!Session::has('coupon')){
            $this->CartService->autoAppliedCoupon();
        }

        $cartCount = CartData::where('cart_id', $cartId)->sum('quantity');
        isset($cartCount) && !empty($cartCount) ? $cartCount : 0;
        return response()->json(['error' => false, 'message' => 'Cart updated', 'count' => $cartCount]);
    }

    public function cart()
    {
        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        }else{
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        $session_id = Session::getId();

        $countries = Country::with('states')->find(14);

        $CartTotal = $this->CartService->getCartTotal();
        $shipping = $this->CartService->getShippingCharge();

        $page_content = ["meta_title"=>config('constant.pages_meta.cart.meta_title'),"meta_description"=>config('constant.pages_meta.cart.meta_description')];

        if(!empty($cart))
        {
            return view('front-end.cart',compact('cart','CartTotal','shipping','countries','page_content'));
          }
          else
           {

             return redirect('shop');
           }
    }

    public function removeFromCart($product_id)
    {
        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->first();
        }else{
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->first();
        }

        if ($cart) {
            $CartData = CartData::where('cart_id', $cart->id)
                                ->where('id', $product_id)
                                ->delete();
        }
        return redirect()->route('cart')->with('success','Item removed from cart');
    }

    public function applyCoupon(Request $request)
     {
       $coupon = Coupon::where('code', $request->coupon_code)->where('is_active', true)->first();
       $total = $this->CartService->getCartTotal();
       $cart = [];

        if(empty($coupon) && !isset($coupon)){
            return ['success' => false, 'message' => 'Coupon is not valid.'];
        }

        if (!$coupon) {
            return ['success' => false, 'message' => 'Coupon does not exist'];
        }

        $currentDate = date('Y-m-d');

        if($coupon->start_date > $currentDate){
            return ['success' => false, 'message' => 'Coupon is not yet valid'];
        }

        if($coupon->end_date < $currentDate){
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
        }else{
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

       if (!$cart) {
            return ['success' => false, 'message' => 'Cart is empty'];
        }

        if ($total['subtotal'] < $coupon->minimum_cart_total) {
            return ['success' => false, 'message' => 'Cart total is less than the minimum required to apply this coupon'];
        }

        if($total['subtotal'] < $coupon->minimum_spend || $total['subtotal'] > $coupon->maximum_spend)
        {
            return ['success' => false, 'message' => 'you can use this coupon between '.$coupon->minimum_spend.' To '.$coupon->maximum_spend.' amount' ];
        }

        if ($coupon->use_limit && $coupon->used >= $coupon->use_limit) {
            return ['success' => false, 'message' => 'This coupon has reached its usage limit.' ];
        }

        if(isset($coupon->categories) && !empty($coupon->categories) && $coupon->categories != null)
        {
            $couponCategories = explode(',', $coupon->categories);

            foreach ($cart->items as $item) {
                $productCategories = $item->product->product_category->pluck('id')->toArray();
                if (!array_intersect($productCategories, $couponCategories)) {
                    return ['success' => false, 'message' => 'This coupon is not applicable to the items in your cart'];
                }
            }
        }

        if(isset($coupon->products) && !empty($coupon->products) && $coupon->products != null)
        {
            $couponProducts = explode(',', $coupon->products);
            foreach ($cart->items as $item) {
                if (!in_array($item->product->id, $couponProducts)) {
                    return ['success' => false, 'message' => 'This coupon is not applicable to the items in your cart based on product'];
                }
            }
        }

        $amount = 0;
        if($coupon->type == "0")
          {
           $amount = $coupon->amount;

          }
          elseif($coupon->type == "1")
           {
              $amount = ($coupon->amount / 100) * $total['subtotal'];

           }
        $coupon->used++;
        $coupon->save();

        Session::put('coupon', [
            'code' => $coupon->code,
            'discount_amount' => $amount,
        ]);
        return ['success' => true, 'total' => $total['subtotal'] - $coupon->discount_amount];
    }

    public function resetCoupon(){
        Session::forget('coupon');
        return back()->with('success','Coupon code has been reset successfully.');
    }

   public function billingDetails(Request $request)
    {

        $state_name = State::whereId($request->state)->select('name')->first();
        $session_data = ['country'=>$request->country,'state'=>$state_name,'state_id'=>$request->state, 'city'=>$request->city, 'postcode'=>$request->postcode];
        Session::put('billing_details', $session_data);
        return  redirect('cart');
    }

    public function updateCart(Request $request)
    {
        foreach($request->data as $data)
        {
            CartData::whereId($data['rowId'])->update(['quantity'=>$data['quantity']]);
        }

        if(Session::has('coupon'))
        {
            $request_data = request()->merge(['coupon_code' => Session::get('coupon')]);
            $response = $this->applyCoupon($request_data);
            if($response['success'] === false)
            {
            Session::forget('coupon');
            }
        }

        if(!Session::has('coupon')){
            $this->CartService->autoAppliedCoupon();
        }

        session()->flash('success', 'Cart updated successfully.');
    }

    public function getCartCount(Request $request)
    {
        $session_id = Session::getId();

        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items')->first();
        }else{
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
