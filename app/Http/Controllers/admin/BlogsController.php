<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminBlogRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\BlogCategory;
use Barryvdh\DomPDF\Facade\Pdf;


class BlogsController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('category')->latest()->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('sort_order')->get();
        return view('admin.blogs.add', compact('categories'));
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
        Blog::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'image'=>$image,
            'slug'=>$slug,
            'added_by'=>Auth::guard('admin')->id(),
            'blog_category_id'=>$request->blog_category_id,
        ]);

        return redirect()->route('blogs.index')->with('success','Blog is created successfully');
    }

    public function show($slug)
    {  
        $detail = Blog::where('slug',$slug)->first();
        $categories = BlogCategory::orderBy('sort_order')->get();
        return view('admin.blogs.edit',compact('detail', 'categories'));
    }
    
    public function update(AdminBlogRequest $request)
    {
        $slug = Str::slug($request->title);
        $data = [
            'title'=>$request->title,
            'description'=>$request->description,
            'slug'=>$slug,
            'status'=>$request->status,
            'added_by'=>Auth::guard('admin')->id(),
            'blog_category_id'=>$request->blog_category_id,
        ];
       if($request->has('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/admin/uploads/blogs';
            $file->move($destinationPath, $fileName);
            $image =  $destinationPath.'/'.$fileName;
            $data['image']=$image;
        }
        Blog::where('id',$request->blog)->update($data);
    
        return redirect()->route('blogs.index')->with('success', 'Blog post updated successfully');
    }

    public function destroy($blog)
    {
       Blog::where('slug',$blog)->delete();
       return redirect()->route('blogs.index')->with('success','Page deleted successfully');
    }

    public function generateBlogPDF(Blog $blog)
    {
        $data = ['blog'=>$blog];
        $pdf = Pdf::loadView('admin.pdf.blog',$data);
        return $pdf->download($blog->slug.'.pdf');
    }
}
