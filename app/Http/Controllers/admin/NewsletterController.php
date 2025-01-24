<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function allNewsLetter()
     {
        return view('admin.news_letter.list');
     }
}
