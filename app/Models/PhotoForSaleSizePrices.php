<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoForSaleSizePrices extends Model
{
    use HasFactory;
    protected $fillable=[
        'product_id',
        'size_id',
        'type_id',
        'price',
    ];
}
