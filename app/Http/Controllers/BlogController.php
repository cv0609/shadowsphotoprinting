<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
class BlogController extends Controller
{
    public function blog()
    {
        $detail = Blog::get();
        return view('blog',compact('detail'));
    }
}
