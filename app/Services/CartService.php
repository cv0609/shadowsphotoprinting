<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\GiftCardCategory;
use App\Models\PhotoForSaleProduct;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

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
            $coupon = Coupon::where(['code'=>$couponCode['code']])->where('is_active', true)->first();
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

     public function getOrderTotal($OrderNumber)
     {

        $order = Order::where(["order_number"=>$OrderNumber])->with('orderDetails.product')->first();
         $data = [];

         if (!$order) {
             return 0;
         }


         $subtotal = $order->orderDetails->reduce(function ($carry, $item) {

             if($item->product_type == 'gift_card'){
                 $product_price = $item->product_price;
             }else if($item->product_type == 'photo_for_sale'){
                 $product_price = $item->product_price;
             }else{
                 $product_price = $item->product->product_price;
             }
             return $carry + ($product_price * $item->quantity);
         }, 0);

         $couponCode = $order->coupon_code;
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

         if($shipping->status == "1" && isset($order->shipping_charge) && !empty($order->shipping_charge)){
             $shippingCharge = $order->shipping_charge; // Example shipping charge
         }
         else
          {
             $shippingCharge = 0;
          }

         $totalAfterShipping = $totalAfterDiscount + $shippingCharge;
         $data = ['subtotal'=>$subtotal,'total'=>$totalAfterShipping,'coupon_discount' => $discount,"coupon_code"=>$coupon_code,'coupon_id' => $coupon_id,"shippingCharge" => $shippingCharge];
         return $data;


     }

     public function autoAppliedCoupon(){

        $CartTotal = $this->getCartTotal();
        $currentDate = now();

        $coupon = Coupon::where('is_active', '1')
        ->where('auto_applied', '1')
        ->where('start_date', '<=', $currentDate)
        ->where('end_date', '>=', $currentDate)
        ->where('product_category', null)
        ->where('products', null)
        ->where(function($query) use ($CartTotal) {
            $query->where('minimum_spend', '<=', $CartTotal['subtotal'])
                  ->where('maximum_spend', '>=', $CartTotal['subtotal']);
        })
        ->withUsageLimit()
        ->first();
        
        if(isset($coupon) && !empty($coupon)){
            $amount = 0;
            if($coupon->type == "0"){
              $amount = $coupon->amount;
            }
            elseif($coupon->type == "1"){
                $amount = ($coupon->amount / 100) * $CartTotal['subtotal'];
            }
    
            $coupon->used++;
            $coupon->save();
     
            Session::put('coupon', [
                'code' => $coupon->code,
                'discount_amount' => $amount,
            ]);
        }
     }
}
