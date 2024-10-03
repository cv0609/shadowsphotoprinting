<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPrint extends Model
{
    use HasFactory;
    protected $table = 'test_print';
    protected $fillable=[
        'category_id',
        'product_id',
        'product_price',
        'qty',
    ];

    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
