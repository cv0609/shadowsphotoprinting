<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ambassador;
use App\Models\Blog;
use App\Mail\NewAmbassadorAdminNotification;
use App\Mail\BlogSubmittedAdminNotification;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

use App\Http\Requests\AdminBlogRequest;

use Session;

class AmbassadorController extends Controller
{

  public function applyForm()
  {
    $page_content = ["meta_title"=>"Apply to Be a Photography Brand Ambassador Today","meta_description"=>"Fill out our quick application to join the Shadows Photo Printing ambassador team. Open to passionate photographers across Australia—start your journey today."];
    return view('front-end.apply-form',compact('page_content'));
  }

  public function featuredPhotographers()
  {
    $page_content = ["meta_title"=>"Featured Photographers Australia – Showcasing Creative Talent","meta_description"=>"Meet professional photographers from across Australia. Discover inspiring visual artists proudly highlighted by Shadows Photo Printing for their passion and creativity."];

      $specialtyMap = [
        'wedding' => 'Wedding/Engagement/Couples',
        'newborn' => 'Newborn, Maternity & Family',
        'grad' => 'School Photography (Formals, Graduation Ceremonies)',
        'landscape' => 'Landscape/Nature',
        'portraits' => 'Portraits',
        'pets' => 'Pets',
        'boudoir' => 'Boudoir',
        'sports' => 'Sports',
        'lifestyle' => 'Lifestyle/Fashion',
        'other' => 'Other'
      ];

      $ambassadors = Ambassador::where('is_approved',1)->paginate(10);

    return view('front-end.featured-photographers',compact('page_content','specialtyMap','ambassadors'));
  }

  public function photographerBrandAmbassador()
  {
    $page_content = ["meta_title"=>"Photographer Brand Ambassador Australia – Join the Team","meta_description"=>"Become a photographer brand ambassador in Australia with Shadows Photo Printing. Get featured, earn rewards, and grow your creative reach with our community.
"];
    return view('front-end.photographer-brand-ambassador',compact('page_content'));
  }


  public function ambassador(){

    $page_content = ["meta_title"=>"Ambassador Details","meta_description"=>"Ambassador Details"];

    return view('front-end.profile.ambassador',compact('page_content'));
}

  public function saveForm(Request $request)
  {

   // dd($request);

      $validated = $request->validate([
          'name' => 'required|string|max:255',
          'location' => 'required|string|max:255',
          'business' => 'nullable|string|max:255',
          'email' => 'required|email|unique:ambassadors,email',
          'website' => 'required|url',
          'social' => 'required|string|max:255',
          'specialty' => 'required|array|min:1',
          'specialty.*' => 'string|max:255',
          'comments' => 'nullable|string',
          'otherSpecialty' => 'nullable|string|max:255',
          'signatureData' => 'required',
          'date'=> 'required',
      ]);

      $data = [
          'name' => $validated['name'],
          'location' => $validated['location'],
          'business_name' => $validated['business'] ?? null,
          'email' => $validated['email'],
          'website' => $validated['website'],
          'social_media_handle' => $validated['social'],
          'specialty' => implode(',', $validated['specialty']),
          'comments' => $validated['comments'] ?? '',
          'other_specialty' => $validated['otherSpecialty'] ?? null,
          'signature' => $validated['signatureData'],
          'submit_date' =>  $validated['date'],
      ];


     // dd($data);

      $ambassador = Ambassador::create($data);

      Mail::to(env('ADMIN_MAIL'))->send(new NewAmbassadorAdminNotification($ambassador));

      //return redirect()->route('photographer-brandAmbassador')->with('success', 'Application submitted successfully.');
      return redirect()->back()->with('success', 'Thank you! We look forward to reviewing your application! You should hear a response from us within 7-10 days (or sooner).');
  }


  public function blog(){
    $user_id = Auth::user()->id;
    $blogs = Blog::where('user_id',$user_id)->get();

    $page_content['meta_title'] = 'Blog';
    $page_content['meta_description'] = 'Blog';

     return view('front-end.profile.blogs.index',compact('blogs','page_content'));
  }

  public function create(){
    $page_content['meta_title'] = 'Blog';
    $page_content['meta_description'] = 'Blog';

     return view('front-end.profile.blogs.add',compact('page_content'));
  }


  public function save(AdminBlogRequest $request){
    $user_id = Auth::user()->id;
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
    $blog = Blog::create(['title'=>$request->title,'description'=>$request->description,'image'=>$image,'slug'=>$slug,'status'=>'2',"added_by"=>1,'user_id'=>$user_id]);

    Mail::to(env('ADMIN_MAIL'))->send(new BlogSubmittedAdminNotification($blog));

     return redirect()->route('ambassador.blog')->with('success','Blog is submitted successfully');
  }

  public function viewBlog(Request $request,Blog $id){

    $page_content['meta_title'] = 'Blog';
    $page_content['meta_description'] = 'Blog';
     $detail = $id;
     return view('front-end.profile.blogs.edit',compact('page_content','detail'));
  }

  public function saveBlog(AdminBlogRequest $request,Blog $id){

    $blog = $id;
    $user_id = Auth::user()->id;

    $slug = Str::slug($request->title);
    $data = ['title'=>$request->title,'description'=>$request->description,'slug'=>$slug,'status'=>'2',"added_by"=>1,'user_id'=>$user_id];
   if($request->has('image'))
    {
        $file = $request->file('image');
        $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
        $destinationPath = 'assets/admin/uploads/blogs';
        $file->move($destinationPath, $fileName);
        $image =  $destinationPath.'/'.$fileName;
        $data['image']=$image;
    }
    $blog->update($data);

    return redirect()->route('ambassador.blog')->with('success','Blog update submitted successfully');
  }

  public function destroy(Blog $blog)
  {
      $blog->delete();
     return redirect()->route('ambassador.blog')->with('success','Blog post deleted successfully');
  }


}
