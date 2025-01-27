<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartData extends Model
{
    use HasFactory;
    protected $fillable = ['cart_id','product_id','quantity','selected_images','product_type','product_desc','product_price','is_test_print','test_print_price','test_print_qty','watermark_image','test_print_cat'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    

}
