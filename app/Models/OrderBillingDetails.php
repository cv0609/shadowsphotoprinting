<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderBillingDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'fname',
        'lname',
        'street1',
        'street2',
        'state',
        'postcode',
        'phone',
        'suburb',
        'email',
        'username',
        'password',
        // 'stripeToken',
        'ship_fname',
        'ship_lname',
        'ship_company',
        'ship_street1',
        'ship_street2',
        'ship_suburb',
        'ship_state',
        'ship_postcode',
        'order_comments',
        'order_id'
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
}
