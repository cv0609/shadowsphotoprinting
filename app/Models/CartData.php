<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartData extends Model
{
    use HasFactory;
    protected $fillable = ['cart_id','product_id','quantity','selected_images','product_type','product_desc','product_price'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    

}
