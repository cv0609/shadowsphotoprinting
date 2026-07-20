<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShadowsMonthlySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_image',
        'overlay_opacity',
        'hero_heading',
        'hero_subtitle',
        'hero_cta_text',
        'hero_cta_url',
    ];

    protected $casts = [
        'overlay_opacity' => 'integer',
    ];

    public static function current(): self
    {
        $settings = static::query()->first();

        if ($settings) {
            return $settings;
        }

        return static::create([
            'overlay_opacity' => 20,
        ]);
    }
}
