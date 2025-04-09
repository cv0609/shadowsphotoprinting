<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ambassador;

use Illuminate\Support\Facades\Mail;
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

}