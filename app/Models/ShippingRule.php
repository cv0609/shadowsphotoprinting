<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_category_id', 'rule_type', 'condition', 'carrier', 'service',
        'price', 'delivery_time', 'dimensions', 'weight', 'is_active', 'priority'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'dimensions' => 'array',
        'is_active' => 'boolean'
    ];

    public function shippingCategory()
    {
        return $this->belongsTo(ShippingCategory::class);
    }

    public function matchesProduct($product)
    {
        switch ($this->rule_type) {
            case 'size_based':
                return $this->matchesSize($product);
            case 'quantity_based':
                return $this->matchesQuantity($product);
            case 'fixed':
                return true;
            default:
                return false;
        }
    }

    protected function matchesSize($product)
    {
        $productTitle = strtolower(preg_replace('/\s+/', '', $product->product_title));
        $condition = strtolower(preg_replace('/\s+/', '', $this->condition));
        
        return strpos($productTitle, $condition) !== false;
    }

    protected function matchesQuantity($quantity)
    {
        // Parse condition like "1-60", "61-100", "101+"
        if (strpos($this->condition, '-') !== false) {
            [$min, $max] = explode('-', $this->condition);
            return $quantity >= (int)$min && $quantity <= (int)$max;
        } elseif (strpos($this->condition, '+') !== false) {
            $min = (int)str_replace('+', '', $this->condition);
            return $quantity >= $min;
        }
        
        return false;
    }
} 