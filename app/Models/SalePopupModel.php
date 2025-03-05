<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class SalePopupModel extends Model
{
    use HasFactory;
    protected $table = 'sale_popup';
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
        $timezone = Config::get('app.timezone'); // Get the application's current timezone
        $currentDate = Carbon::now($timezone)->format('Y-m-d');

        return $query->where('status', '1')
                      ->whereDate('start_date', '<=', $currentDate)
                      ->whereDate('end_date', '>=', $currentDate);
     }
 
     // Get the top priority sale
     public static function getTopPrioritySale()
     {
         return self::active()
                    ->orderBy('id', 'desc')
                    ->first();
     }

}
