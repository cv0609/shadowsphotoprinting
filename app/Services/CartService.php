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

        if (!$cart) {
            return 0;
        }

        $total = $cart->items->reduce(function ($carry, $item) {
            return $carry + ($item->product->product_price * $item->quantity);
        }, 0);

        return $total;
    }

    public function getShippingCharge()
     {
        $shipping = Shipping::first();
        return $shipping;
     }
}
