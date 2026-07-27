<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyEdition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'month',
        'year',
        'cover_image',
        'intro',
        'welcome_note',
        'editor_note',
        'status',
        'is_homepage',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'month' => 'integer',
        'year' => 'integer',
        'is_homepage' => 'boolean',
    ];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'monthly_edition_blog')
            ->withPivot(['sort_order', 'is_featured'])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function featuredBlogs()
    {
        return $this->belongsToMany(Blog::class, 'monthly_edition_blog')
            ->withPivot(['sort_order', 'is_featured'])
            ->withTimestamps()
            ->wherePivot('is_featured', true)
            ->orderByPivot('sort_order');
    }

    public function sections()
    {
        return $this->hasMany(MonthlyEditionSection::class)
            ->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, (int) $this->month, 1));
    }

    public function getEditionLabelAttribute(): string
    {
        return trim($this->month_name . ' ' . $this->year);
    }
}
