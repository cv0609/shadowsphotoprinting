<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminBlogRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
class BlogsController extends Controller
{
    public function index()
    {
        $blogs = Blog::paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.add');
    }

    public function store(AdminBlogRequest $request)
    {
       $slug = Str::slug($request->title);
       $image = "";
       if($request->has('image'))
         {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/admin/uploads/blogs';
            $file->move($destinationPath, $fileName);
            $image =  $destinationPath.'/'.$fileName;
         }
           Blog::insert(['title'=>$request->title,'description'=>$request->description,'image'=>$image,'slug'=>$slug,"added_by"=>Auth::guard('admin')->id()]);

           return redirect()->route('blogs.index')->with('success','Blog is created successfully');
    }

    public function show($blog)
    {
      $detail = Blog::where('slug',$blog)->first();
      $blog_fields = read_json($detail->slug.'.json');
      return view('admin.blogs.edit',compact('detail','page_fields'));
    }
}
