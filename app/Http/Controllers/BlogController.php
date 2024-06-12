<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
class BlogController extends Controller
{
    public function blogDetail($slug)
    {
        $blog_details = Blog::where('slug',$slug)->first();
        return view('blog_detail',compact('blog_details'));
    }
}
