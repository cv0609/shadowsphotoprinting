<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardCategory extends Model
{
    use HasFactory;

    protected $table = 'gift_card_category';
    protected $fillable = [
        'name',
        'slug',
        'image'
    ];
}
