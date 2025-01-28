<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newzletter;
use App\Http\Requests\NewzLetterRequest;
use Illuminate\Support\Facades\Auth;

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

   public function saveNewsLetter(NewzLetterRequest $request)
    {
      $slug = \Str::slug($request->title);
      $imagee = "";
      if($request->image)
       {
           $file = $request->file('image');
           $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
           $destinationPath = 'assets/admin/uploads/blogs';
           $file->move($destinationPath, $fileName);
           $imagee =  $destinationPath.'/'.$fileName;
       }
       Newzletter::insert(['title'=>$request->title,'content'=>$request->content,'image'=>$imagee,'slug'=>$slug,"added_by"=>Auth::guard('admin')->id()]);

       return redirect()->route('news-letter')->with('success','Newzletter is created successfully');
    }  

    public function updateStatus(Request $request){
      Newzletter::where('id', $request->newzletter_id)->update(['is_active' => $request->checkedValue]);
      return response()->json(['error' => false, 'message' => 'Status updated successfully.','checked' =>$request->checkedValue]);
   }

   public function newsletterDistroy($id)
   {
      $coupon_detail = Newzletter::whereId($id)->delete();
      return redirect()->route('news-letter')->with('success','News letter is deleted successfully');
   }

   public function editnewsletter($id)
    {
      $detail = Newzletter::where('id', $id)->first();
      return view('admin.news_letter.edit',compact('detail'));
       
    }
}
