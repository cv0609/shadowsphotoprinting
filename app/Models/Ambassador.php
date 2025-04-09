<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambassador extends Model
{
    use HasFactory;

    protected $table = 'ambassadors';

    protected $fillable = [
        'name',
        'location',
        'business_name',
        'email',
        'website',
        'social_media_handle',
        'specialty',
        'comments',
        'other_specialty',
        'is_approved',
        'user_id',
    ];

    /**
     * Get the user that this ambassador is associated with.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
