<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
    protected $fillable = ['user_email','coupon_id','session_id'];

    public function items()
    {
        return $this->hasMany(CartData::class);
    }

    public static function getCartCount()
    {
        if (Auth::check()) {
            // User is logged in, sum quantities by user ID
            $userId = Auth::id();
            return self::where('user_email', $userId)
                        ->with('items')
                        ->get()
                        ->sum(function($cart) {
                            return $cart->items->sum('quantity');
                        });
        } else {
            // User is not logged in, sum quantities by session ID
            $sessionId = Session::getId();
            return self::where('session_id', $sessionId)
                        ->with('items')
                        ->get()
                        ->sum(function($cart) {
                            return $cart->items->sum('quantity');
                        });
        }
    }

}
