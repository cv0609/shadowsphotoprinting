<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ambassador;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Affiliate;
use App\Mail\ApprovedAmbassadorNotification;


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use Session;

class BrandAmbassadorController extends Controller
{
  
  public function index()
  {
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

      $ambassadors = Ambassador::where('is_approved',1)->with(['user'])->paginate(10);
      return view('admin.ambassador.index', compact('ambassadors','specialtyMap'));
  }

  public function request()
  {
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

      $ambassadors = Ambassador::where('is_approved', '!=', 1)->paginate(10);

     return view('admin.ambassador.request', compact('ambassadors','specialtyMap'));
  }


  public function approve($id)
  {
      $ambassador = Ambassador::findOrFail($id);
      $ambassador->is_approved = true;
      $ambassador->save();

      $password = $this->createAffliateUser($ambassador);

      Mail::to($ambassador->email)->send(new ApprovedAmbassadorNotification($ambassador,$password));

      return redirect()->route('brand.index')->with('success', 'Ambassador approved successfully!');
  }


  public function reject($id)
  {
      $ambassador = Ambassador::findOrFail($id);
      $ambassador->is_approved = 2;
      $ambassador->save();

      //$password = $this->createAffliateUser($ambassador);

      //Mail::to($ambassador->email)->send(new ApprovedAmbassadorNotification($ambassador,$password));

      return redirect()->route('brand.requests')->with('success', 'Ambassador rejected successfully!');
  }

   
  public function createAffliateUser(Ambassador $ambassador ){
    $password = null;
     $user = User::where('email',$ambassador->email)->first();
      if(!$user){
        $password = Str::random(8);
        //$password = "PASSWORD";
        $name = explode(' ',$ambassador->name);

        $hashedPassword = Hash::make($password);
          $user = User::create([
            'username' => $ambassador->email,
            'first_name'=>$name[0]??'',
            'last_name'=>$name[1]??'',
            'role' => 'affiliate',
            'email' => $ambassador->email,
            'password' => $hashedPassword,
            'is_email_verified' => '1',
        ]);
      }else{
        $user->update(['role'=>'affiliate']);
      }
   
       $referral_code = Affiliate::generateUniqueCode();

       $coupon = Coupon::create(['code'=>$referral_code,'type'=>'1','amount'=>20,'auto_applied'=>0,'is_active'=>1]);

     if($coupon){
        $data = [
          'referral_code'=>$referral_code,
          'coupon_code'=>$referral_code,
          'referral_count'=>0,
          'referral_sales_count'=>0,
          'commission'=>0,
          'user_id'=>$user->id,
        ];
       $affiliate =  Affiliate::create($data);

       if($affiliate){
        $ambassador->update(['user_id'=>$user->id]);
       }

     }
   
     return $password;

  }



}