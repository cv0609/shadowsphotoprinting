<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
        'product_title',
        'product_description',
        'product_price',
        'type_of_paper_use',
        'product_image'
    ];

    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
  
  
}