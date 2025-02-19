<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePopUp extends Model
{
    protected $table = 'sale_popup';
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'start_date',
        'end_date',
        'status',
    ];

     // Scope to get active and current date sales
     public function scopeActive($query)
     {
        return $query->where('status', '1')
                      ->whereDate('start_date', '<=', now())
                      ->whereDate('end_date', '>=', now());
     }
 
     // Get the top priority sale
     public static function getTopPrioritySale()
     {
         return self::active()
                    ->orderBy('id', 'desc')
                    ->first();
     }

}
