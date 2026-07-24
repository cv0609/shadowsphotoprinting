<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyEditionSection extends Model
{
    use HasFactory;

    public const PLACEMENT_BEFORE = 'before_featured';
    public const PLACEMENT_AFTER = 'after_featured';

    public const IMAGE_FULL = 'full_width';
    public const IMAGE_LEFT = 'left';
    public const IMAGE_RIGHT = 'right';
    public const IMAGE_CENTERED = 'centered';

    protected $fillable = [
        'monthly_edition_id',
        'title',
        'icon',
        'content',
        'image',
        'image_caption',
        'image_position',
        'placement',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function edition()
    {
        return $this->belongsTo(MonthlyEdition::class, 'monthly_edition_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function isAfterFeatured(): bool
    {
        return $this->placement === self::PLACEMENT_AFTER;
    }
}
