<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'image'
    ];

    public function products()
    {
        return $this->hasMany(Product::class,'category_id');
    }

    public function getProductStock()
    {
        return $this->hasOne(ProductStock::class,'product_category_type_id');
    }
}
