<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Affiliate extends Model
{
    use HasFactory;

    protected $table = 'affiliates';

    protected $fillable = [
          'referral_code',
          'coupon_code',
          'referral_count',
          'referral_sales_count',
          'commission',
          'user_id',
    ];

    /**
     * Get the user associated with the affiliate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     /**
     * generateUniqueCode method for generating unique code for affiliate.
     */

    public static function generateUniqueCode($prefix = 'REF-', $length = 6): string
    {
        do {
            $code = $prefix . strtoupper(Str::random($length));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }
}
