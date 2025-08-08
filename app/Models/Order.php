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
        'shipping_breakdown',
        'shipping_service',
        'shipping_carrier',
        'total',
        'payment_id',
        'is_paid',
        'order_status',
        'payment_status',
        'payment_method',
        'order_type'
    ];

    protected $casts = [
        'shipping_breakdown' => 'array',
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

    /**
     * Get category display name for shipping breakdown
     */
    public function getCategoryDisplayName($category)
    {
        $names = [
            'scrapbook_page_printing' => 'Scrapbook Page Printing',
            'photo_prints' => 'Photo Prints',
            'canvas' => 'Canvas Prints',
            'photo_enlargements' => 'Photo Enlargements',
            'posters' => 'Posters',
            'hand_craft' => 'Hand Craft',
            'photos_for_sale' => 'Photos for Sale',
            'gift_card' => 'Gift Card',
            'test_prints' => 'Test Prints'
        ];
        
        return $names[$category] ?? ucwords(str_replace('_', ' ', $category));
    }

    /**
     * Get service display name for shipping breakdown
     */
    public function getServiceDisplayName($service)
    {
        $names = [
            'snail_mail' => 'Snail Mail',
            'express' => 'Express'
        ];
        
        return $names[$service] ?? ucwords(str_replace('_', ' ', $service));
    }
}
