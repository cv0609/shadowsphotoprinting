<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'image',
        'slug',
        'status',
        'added_by',
        'user_id',
        'blog_category_id',
    ];
     
    /**
     * Get the user associated with the affiliate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function monthlyEditions()
    {
        return $this->belongsToMany(MonthlyEdition::class, 'monthly_edition_blog')
            ->withPivot('sort_order')
            ->withTimestamps();
    }
}
