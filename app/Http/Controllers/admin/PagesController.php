<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPageRequest;

class PagesController extends Controller
{
    public function index()
    {
        return view('admin.pages.index');
    }

    public function create()
     {
         return view('admin.pages.add');
     }

    public function store(AdminPageRequest $request)
     {
        dd($request->all());
     }
}
