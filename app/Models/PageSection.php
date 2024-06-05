<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'page_id',
        'content',
    ];

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_section_page', 'page_section_id', 'page_id');
    }
}