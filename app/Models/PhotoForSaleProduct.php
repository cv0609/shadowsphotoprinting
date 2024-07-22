<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoForSaleProduct extends Model
{
    use HasFactory;
    protected $fillable=[
        'product_title',
        'slug',
        'min_price',
        'max_price',
        'product_description',
        'product_image',
    ];

    public function product_category()
    {
        return $this->belongsTo(PhotoForSaleCategory::class, 'category_id');
    }

    public function productSizeType()
    {
        return $this->hasMany(PhotoForSaleSizePrices::class,'product_id');
    }
}
