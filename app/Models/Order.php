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
        'shutter_point',
        'commission',
        'sub_total',
        'shipping_charge',
        'total',
        'payment_id',
        'is_paid',
        'order_status',
        'payment_status',
        'payment_method',
        'order_type'
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

    public function orderBillingShippingDetails()
    {
        return $this->hasOne(OrderBillingDetails::class,'order_id','id');
    }
}
