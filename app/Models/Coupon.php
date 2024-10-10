<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'object_type',
        'vendor_id',
        'code',
        'type',
        'amount',
        'minimum_spend',
        'maximum_spend',
        'start_date',
        'end_date',
        'products',
        'auto_applied',
        'use_limit',
        'same_ip_limit',
        'use_limit_per_user',
        'use_device',
        'multiple_use',
        'total_use',
        'usage_limit',
        'used',
        'product_category',
        'coupon_code',
        'qty'
    ];

    // public function isExpired()
    // {
    //     return $this->end_date && Carbon::parse($this->end_date)->isPast();
    // }

    // public function isStarted() {
    //     return $this->start_date && Carbon::parse($this->start_date)->isPast();
    // }

    public function canBeUsed()
    {
        return !$this->usage_limit || $this->used < $this->usage_limit;
    }

    public function scopeWithUsageLimit($query)
    {
        return $query->where(function($query) {
            $query->whereNotNull('use_limit')
                ->where('used', '<', DB::raw('use_limit'));
        });
    }
}
