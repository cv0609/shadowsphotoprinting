<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'fname',
        'lname',
        'street1',
        'street2',
        'state',
        'company_name',
        'country_region',
        'postcode',
        'phone',
        'suburb',
        'email',
        'username',
        'password',
        'ship_fname',
        'ship_lname',
        'ship_company',
        'ship_street1',
        'ship_street2',
        'ship_suburb',
        'ship_state',
        'ship_postcode',
        'order_comments',
        'order_id',
        'isShippingAddress',
        'ship_country_region'
    ];

}

