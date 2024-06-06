<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    public function index()
    {
        return view('admin.blogs.index');
    }

    public function create()
     {
         return view('admin.blogs.add');
     }
}
