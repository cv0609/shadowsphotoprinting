<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ambassador;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use Session;

class AmbassadorController extends Controller
{



  public function applyForm()
  {
    $page_content = ["meta_title"=>"Apply Form","meta_description"=>"Apply Form"];
    return view('front-end.apply-form',compact('page_content'));
  } 

  public function featuredPhotographers()
  {
    $page_content = ["meta_title"=>"featured Photographers","meta_description"=>"featured Photographers"];

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

    return view('front-end.featured-photographers',compact('page_content','specialtyMap','ambassadors'));
  } 

  public function photographerBrandAmbassador()
  {
    $page_content = ["meta_title"=>"photographer Brand Ambassador","meta_description"=>"photographer Brand Ambassador"];
    return view('front-end.photographer-brand-ambassador',compact('page_content'));
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

      Ambassador::create($data);
  
      //return redirect()->route('photographer-brandAmbassador')->with('success', 'Application submitted successfully.');
      return redirect()->back()->with('success', 'Thank you! We look forward to reviewing your application! You should hear a response from us within 7-10 days (or sooner).
');
  }
  

}