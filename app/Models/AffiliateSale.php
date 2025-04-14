<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateSale extends Model
{
    use HasFactory;

    protected $table = 'affiliate_sales';

    protected $fillable = [
        'affiliate_id',
        'order_id',
        'commission',
        'shutter_points',
        'operation',
    ];

    /**
     * Get the affiliate that owns the sale.
     */
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Get the order associated with the sale.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
