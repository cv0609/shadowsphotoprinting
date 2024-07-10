<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Shipping;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\GiftCardCategory;
use App\Models\PhotoForSaleProduct;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    public function getCartTotal()
    {
        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
        }else{
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
        }

        $data = [];

        if (!$cart) {
            return 0;
        }


        $subtotal = $cart->items->reduce(function ($carry, $item) {

            if($item->product_type == 'gift_card'){
                $product_price = $item->product_price;
            }else if($item->product_type == 'photo_for_sale'){
                $product_price = $item->product_price;
            }else{
                $product_price = $item->product->product_price;
            }
            return $carry + ($product_price * $item->quantity);
        }, 0);

        $couponCode = Session::get('coupon'); // Assume coupon code is stored in session
        $discount = 0;
        $coupon_code = "";
        $coupon_id = "";
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            $coupon_code = $couponCode;
            if ($coupon) {
                if ($coupon->type == '1') {
                    $discount = ($subtotal * $coupon->amount) / 100;
                } elseif ($coupon->type == '0') {
                    $discount = $coupon->amount;
                }
                // Ensure the discount does not exceed the total
                $discount = min($discount, $subtotal);
                $coupon_id = $coupon->id;
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
        $data = ['subtotal'=>$subtotal,'total'=>$totalAfterShipping,'coupon_discount' => $discount,"coupon_code"=>$coupon_code,'coupon_id' => $coupon_id,"shippingCharge" => $shippingCharge];
        return $data;


    }

    public function getProductDetailsByType($product_id,$product_type)
    {
        switch ($product_type) {
                case 'shop':
                    $product = Product::whereId($product_id)->first();
                    break;
                case 'gift_card':
                    $product = GiftCardCategory::whereId($product_id)->first();
                    break;
                case 'photo_for_sale':
                    $product = PhotoForSaleProduct::whereId($product_id)->first();
                    break;
                default:
                $product = null;
                    break;
            }
        return $product;
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
