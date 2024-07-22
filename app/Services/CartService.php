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

     public function autoAppliedCoupon($subtotal){

        $currentDate = now();

        $coupons = Coupon::where('is_active', true)
        ->where('auto_applied', true)
        ->where('start_date', '<=', $currentDate)
        ->where('end_date', '>=', $currentDate)
        ->get();
        
        $total = $this->getCartTotal();
        $cart = [];
         // $product = Product::find($request->product_id);
         // $productCategories = $product->categories->pluck('id')->toArray();
         if(empty($coupon) && !isset($coupon)){
             return ['success' => false, 'message' => 'Coupon is not valid.'];
         }
        
         if ($currentDate < $coupon->start_date) {
             return ['success' => false, 'message' => 'Coupon has expired'];
         }
 
         if (!$coupon) {
             return ['success' => false, 'message' => 'Coupon does not exist'];
         }
 
         if ($coupon->isExpired()) {
             return ['success' => false, 'message' => 'Coupon has expired'];
         }
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
 
         if ($coupon->use_limit && $coupon->total_use >= $coupon->use_limit) {
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

}
