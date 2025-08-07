<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ShippingTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'min_quantity',
        'max_quantity',
        'snail_mail_price',
        'express_price',
        'is_active'
    ];

    protected $casts = [
        'snail_mail_price' => 'decimal:2',
        'express_price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Get shipping tier for a given quantity
     */
    public static function getTierForQuantity($category, $quantity)
    {
        $query = self::where('category', $category)
            ->where('is_active', true)
            ->where('min_quantity', '<=', $quantity)
            ->where(function ($query) use ($quantity) {
                $query->where('max_quantity', '>=', $quantity)
                      ->orWhereNull('max_quantity');
            })
            ->orderBy('min_quantity', 'desc');
            
        // Debug logging
        Log::info('Shipping tier query', [
            'category' => $category,
            'quantity' => $quantity,
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);
        
        $result = $query->first();
        
        Log::info('Shipping tier result', [
            'found' => $result ? true : false,
            'tier' => $result ? [
                'id' => $result->id,
                'min_quantity' => $result->min_quantity,
                'max_quantity' => $result->max_quantity,
                'snail_mail_price' => $result->snail_mail_price,
                'express_price' => $result->express_price
            ] : null
        ]);
        
        return $result;
    }

    /**
     * Get all tiers for a category
     */
    public static function getTiersForCategory($category)
    {
        return self::where('category', $category)
            ->where('is_active', true)
            ->orderBy('min_quantity', 'asc')
            ->get();
    }

    /**
     * Calculate shipping price based on service type
     */
    public function getPrice($serviceType)
    {
        return $serviceType === 'express' ? $this->express_price : $this->snail_mail_price;
    }
}
