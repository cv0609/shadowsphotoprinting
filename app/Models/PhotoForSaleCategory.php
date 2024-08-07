<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoForSaleCategory extends Model
{
    use HasFactory;
    protected $table = 'photo_for_sale_category';
    protected $fillable = [
        'product_title',
        'slug',
        'image'
    ];

    public function products()
    {
        return $this->hasMany(PhotoForSaleProduct::class,'category_id');
    }


}
