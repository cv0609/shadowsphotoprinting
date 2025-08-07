<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'display_name', 'pricing_type', 'carriers', 'is_active'
    ];

    protected $casts = [
        'carriers' => 'array',
        'is_active' => 'boolean'
    ];

    public function rules()
    {
        return $this->hasMany(ShippingRule::class)->orderBy('priority');
    }

    public function productMappings()
    {
        return $this->hasMany(ProductShippingMapping::class);
    }

    public function getRulesForProduct($product)
    {
        return $this->rules()
            ->where('is_active', true)
            ->orderBy('priority')
            ->get();
    }
} 