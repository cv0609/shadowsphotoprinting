<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AfterPayLogs extends Model
{
    use HasFactory;
    protected $table = 'afterpay_logs';
    protected $fillable = [
        'logs',
    ];
     
    
}
