<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoForSale extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'image'
    ];
    protected $table = 'photo_for_sale_category';
}
