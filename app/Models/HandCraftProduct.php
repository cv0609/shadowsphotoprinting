<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandCraftProduct extends Model
{
    use HasFactory;
    protected $table = "hand_craft_product";    
    protected $fillable=[
        'product_title',
        'category_id',
        'slug',
        'price',
        'product_description',
        'product_image',
        'sold',
    ];

    public function product_category()
    {
        return $this->belongsTo(HandCraftCategory::class, 'category_id');
    }

    public function productSizeType()
    {
        return $this->hasMany(HandCraftCategory::class,'product_id');
    }
}
