<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model 
{
    use HasFactory;
    protected $table = 'product_stock';
    protected $fillable = [
        'product_category_type_id', 'category_id','product_id','qty'
    ];
}
