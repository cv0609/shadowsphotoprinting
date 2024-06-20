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
       $user_id = Auth::loginUsingId();
       Cart::insert(["user_id"=>$user_id,"coupon_id"=>"","total"=>$request->total,"shipping_cost"=>"","grand_total"=>$request->total]);
    }
}
