<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandCraftCategory extends Model
{
    use HasFactory;
    protected $table = 'hand_craft_category';
    
    protected $fillable = [
        'name',
        'slug',
        'image'
    ];

    public function products()
    {
        return $this->hasMany(HandCraftProduct::class,'category_id');
    }


}
