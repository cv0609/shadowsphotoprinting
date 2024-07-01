<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Shipping;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCartTotal()
    {
        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        $data = [];
        if (!$cart) {
            return 0;
        }

        $subtotal = $cart->items->reduce(function ($carry, $item) {
            return $carry + ($item->product->product_price * $item->quantity);
        }, 0);

        $couponCode = Session::get('coupon'); // Assume coupon code is stored in session
        $discount = 0;
        $coupon_code = "";
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            $coupon_code = $couponCode;
            if ($coupon) {
                if ($coupon->type == '1') {
                    $discount = ($subtotal * $coupon->value) / 100;
                } elseif ($coupon->type == '0') {
                    $discount = $coupon->value;
                }

                // Ensure the discount does not exceed the total
                $discount = min($discount, $subtotal);
            }
        }

        // Calculate the total after applying the discount
        $totalAfterDiscount = $subtotal - $discount;
        $shipping = $this->getShippingCharge();

        if($shipping->status == "1" && Session::has('billing_details')){
            $shippingCharge = $shipping->amount; // Example shipping charge
        }
        else
         {
            $shippingCharge = 0;
         }

        $totalAfterShipping = $totalAfterDiscount + $shippingCharge;
        $data = ['subtotal'=>$subtotal,'total'=>$totalAfterShipping,'coupon_discount' => $discount,"coupon_code"=>$coupon_code];
        return $data;


    }

    public function getShippingCharge()
     {
        $shipping = Shipping::first();
        return $shipping;
     }

    public function getCartCount()
     {

     }

}
