<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function photoForSaleSizePrices()
    {
        return $this->hasMany(PhotoForSaleSizePrices::class, 'type_id');
    }
}
