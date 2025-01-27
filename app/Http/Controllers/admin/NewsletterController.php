<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newzletter;
use App\Http\Requests\NewzLetterRequest;

class NewsletterController extends Controller
{
    public function allNewsLetter()
     {
       $newzletters = Newzletter::paginate(10); 
       return view('admin.news_letter.list',compact('newzletters'));
     }

   public function addNewsLetter()
    {
      return view('admin.news_letter.add');
    }  

   public function aveNewsLetter(NewzLetterRequest $request)
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
       Newzletter::insert(['title'=>$request->title,'content'=>$request->description,'image'=>$image,'slug'=>$slug,"added_by"=>Auth::guard('admin')->id()]);

       return redirect()->route('news-letter')->with('success','Newzletter is created successfully');
    }  
}
