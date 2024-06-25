<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartData;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\CartService;
use Session;

class CartController extends Controller
{
    protected $CartService;
    public function __construct(CartService $CartService)
    {
        $this->CartService = $CartService;
    }
    public function addToCart(Request $request)
    {
       $session_id = Session::getId();
       $cart = Cart::firstOrCreate(["user_email"=>"","coupon_id"=>null,"session_id"=>$session_id]);

       if($cart)
         {
            $cart_items = $request->cart_items;
            $cartId = $cart->id;
            foreach($cart_items as $cart_item)
              {
                $Images = [];
                $data = ["cart_id"=>$cartId,"product_id"=>$cart_item['product_id'],"quantity"=>$cart_item['quantity']];
                if(isset($request->selectedImages))
                 {
                    foreach($request->selectedImages as $selectedImages)
                    {
                        $tempImagePath = $selectedImages;
                        $permanentImagePath = '/assets/images/order_images/' . basename($tempImagePath);

                        // Move the image from temp to permanent storage
                        Storage::disk('public')->move($tempImagePath, $permanentImagePath);
                        $Images[] = $permanentImagePath;

                    }

                    $data['selected_images'] =  implode(',',$Images);

                 }

                CartData::insert($data);
              }
         }
    }

    public function cart()
    {
        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        $total = $this->CartService->getCartTotal();
        $shipping = $this->CartService->getShippingCharge();
        return view('front-end.cart',compact('cart','total','shipping'));
    }

    public function removeFromCart($product_id)
    {
        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->first();

        if ($cart) {
            $CartData = CartData::where('cart_id', $cart->id)
                                ->where('product_id', $product_id)
                                ->delete();
        }

        return redirect()->route('cart')->with('success','Item removed from cart');
    }

    public function applyCoupon(Request $request)
     {
        $coupon = Coupon::where('code', $request->coupon_code)->first();
        $total = $this->CartService->getCartTotal();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Coupon does not exist'];
        }

        if ($coupon->isExpired()) {
            return ['success' => false, 'message' => 'Coupon has expired'];
        }

        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->with('items.product')->first();

        if (!$cart) {
            return ['success' => false, 'message' => 'Cart is empty'];
        }

        if ($total < $coupon->minimum_cart_total) {
            return ['success' => false, 'message' => 'Cart total is less than the minimum required to apply this coupon'];
        }

        if($total < $coupon->minimum_spend || $total > $coupon->maximum_spend)
        {
            return ['success' => false, 'message' => 'you can use this coupon between '.$coupon->minimum_spend.' To '.$coupon->maximum_spend.'amount' ];
        }


        // Mark the coupon as used
        $coupon->used++;
        $coupon->save();

        Session::put('coupon', [
            'code' => $coupon->code,
            'discount_amount' => $coupon->amount,
        ]);
        return ['success' => true, 'total' => $total - $coupon->discount_amount];

     }
}
