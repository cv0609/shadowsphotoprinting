<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    public function pageSections()
    {
        return $this->belongsToMany(PageSection::class, 'page_section_page');
    }
}
