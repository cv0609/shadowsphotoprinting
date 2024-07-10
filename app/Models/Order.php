<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_session_id',
        'order_number',
        'coupon_id',
        'coupon_code',
        'discount',
        'sub_total',
        'shipping_charge',
        'total',
        'payment_id',
        'is_paid',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . strtoupper(Str::random(10));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    public function OrderBillingDetail()
    {
        return $this->hasOne(OrderBillingDetails::class);
    }
}
