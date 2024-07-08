<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\CartData;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $session_id = Session::getId();

        if (Auth::check() && !empty(Auth::user())) {
            $auth_id = Auth::user()->id;
            $cart = Cart::where('user_id', $auth_id)->first();
        }else{
            $session_id = Session::getId();
            $cart = Cart::where('session_id', $session_id)->first();
        }

        if ($cart) {
            $cart_data = CartData::where('cart_id', $cart->id)->get();
            if (count($cart_data) > 0) {
                return $next($request);
            }
        }

        return redirect('/shop');
    }
}
