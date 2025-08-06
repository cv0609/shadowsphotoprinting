<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductShippingMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id', 'shipping_category_id', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function shippingCategory()
    {
        return $this->belongsTo(ShippingCategory::class);
    }

    public static function getShippingCategoryForProduct($productCategoryId)
    {
        $mapping = self::where('product_category_id', $productCategoryId)
            ->where('is_active', true)
            ->with('shippingCategory')
            ->first();
            
        return $mapping ? $mapping->shippingCategory : null;
    }
} 