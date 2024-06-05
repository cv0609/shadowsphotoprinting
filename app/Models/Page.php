<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $fillable = [
        'page_title',
        'slug',
        'status',
        'added_by_admin',
    ];

    public function pageSections()
    {
        return $this->belongsToMany(PageSection::class, 'page_section_page', 'page_id', 'page_section_id');
    }
}
