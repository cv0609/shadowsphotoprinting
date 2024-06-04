<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Page;

class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::get();
        return view('admin.pages.index',compact('pages'));
    }

    public function create()
     {
         return view('admin.pages.add');
     }

    public function store(AdminPageRequest $request)
     {
        $slug = Str::slug($request->page_title);
        Page::insert(['page_title'=>$request->page_title,'slug'=>$slug,"added_by_admin"=>Auth::guard('admin')->id()]);
        return redirect()->route('pages.index')->with('success','Page is created successfully');
     }

    public function show($page)
      {
        $detail = Page::whereId($page)->first();
        $page_fields = read_json($detail->slug.'.json');
        dd($page_fields);
        return view('admin.pages.edit',compact('detail','page_fields'));
      }
}
