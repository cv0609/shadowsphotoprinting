<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeLogs extends Model
{
    use HasFactory;
    protected $table = 'stripe_logs';
    protected $fillable = [
        'logs',
    ];
     
    
}
