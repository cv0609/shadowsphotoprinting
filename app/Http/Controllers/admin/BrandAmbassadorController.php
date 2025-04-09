<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ambassador;
use App\Models\User;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Session;

class BrandAmbassadorController extends Controller
{
  
  public function index()
  {
    $specialtyMap = [
      'wedding' => 'Wedding/Engagement/Couples',
      'newborn' => 'Newborn/Family',
      'grad' => 'Grad/Senior Photos',
      'landscape' => 'Landscape/Nature',
      'portraits' => 'Portraits',
      'pets' => 'Pets',
      'boudoir' => 'Boudoir',
      'sports' => 'Sports',
      'lifestyle' => 'Lifestyle/Fashion',
      'other' => 'Other'
     ];

      $ambassadors = Ambassador::where('is_approved',1)->paginate(10);
      return view('admin.ambassador.index', compact('ambassadors','specialtyMap'));
  }

  public function request()
  {
    $specialtyMap = [
      'wedding' => 'Wedding/Engagement/Couples',
      'newborn' => 'Newborn/Family',
      'grad' => 'Grad/Senior Photos',
      'landscape' => 'Landscape/Nature',
      'portraits' => 'Portraits',
      'pets' => 'Pets',
      'boudoir' => 'Boudoir',
      'sports' => 'Sports',
      'lifestyle' => 'Lifestyle/Fashion',
      'other' => 'Other'
     ];

      $ambassadors = Ambassador::where('is_approved',0)->paginate(10);

     return view('admin.ambassador.request', compact('ambassadors','specialtyMap'));
  }


  public function approve($id)
  {
      $ambassador = Ambassador::findOrFail($id);
      $ambassador->is_approved = true;
      $ambassador->save();

      return redirect()->route('brand.index')->with('success', 'Ambassador approved successfully!');
  }

   
  public function createAffliateUser(Ambassador $ambassador ){

     $user = User::where('email',$ambassador->email)->first();
      if(!$user){
        $password = Str::random(8);
        $hashedPassword = Hash::make($password);
        $user = User::insert(['username'=>$ambassador->email, 'role' => 'affliate','email'=>$ambassador->email,'password'=>$hashedPassword,'is_email_verified'=>1]);
      }
   
      if(isset($user) && !empty($user)){
          $user = User::where(['email' =>$request->email])->first();

          $urls = route('email.verify', [
              'token' => base64_encode($request->email),
          ]);

          $data = [
              'email_verify_url' => $urls,
              'username' => $user->username,
          ];
          Mail::to($request->email)->send(new RegisterMail($data));
      }

  }



}