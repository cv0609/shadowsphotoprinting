<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartData;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
       $cart = Cart::create(["user_id"=>"","coupon_id"=>null,"total"=>$request->total,"shipping_cost"=>0,"grand_total"=>$request->total]);

       if($cart)
         {
            $cart_items = $request->cart_items;
            $cartId = $cart->id;
            foreach($cart_items as $cart_item)
              {
                CartData::insert(["cart_id"=>$cartId,"product_id"=>$cart_item['product_id'],"quantity"=>$cart_item['quantity']]);
              }
         }
    }
}
