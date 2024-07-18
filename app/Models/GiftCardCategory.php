<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardCategory extends Model
{
    use HasFactory;

    protected $table = 'gift_card_category';
    protected $fillable = [
        'product_title',
        'slug',
        'product_image'
    ];

    public function giftcards()
    {
        return $this->hasMany(GiftCardCategory::class,'slug');
    }
}
