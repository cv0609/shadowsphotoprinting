<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
    protected $fillable = ['user_email','coupon_id','session_id'];

    public function items()
    {
        return $this->hasMany(CartData::class);
    }

}
