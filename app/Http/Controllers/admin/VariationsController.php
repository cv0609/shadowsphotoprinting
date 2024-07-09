<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;
use App\Http\Requests\SizeRequest;

class VariationsController extends Controller
{
   public function sizes()
    {
      $sizes = Size::paginate();
      return view('admin.Variations.sizes.index',compact('sizes'));
    }

   public function addSize()
     {
        return view('admin.Variations.sizes.add');
     }

    public function saveSize(SizeRequest $request)
     {
        Size::insert(['name'=>$request->name]);
        return redirect()->route('sizes-list')->with('success','Size added successfully');
     }

     public function sizesType()
     {
       $sizes = Size::paginate();
       return view('admin.Variations.sizes.index',compact('sizes'));
     }

    public function addSizeType()
      {
         return view('admin.Variations.sizes.add');
      }

     public function saveSizeType(SizeRequest $request)
      {
         Size::insert(['name'=>$request->name]);
         return redirect()->route('sizes-list')->with('success','Size added successfully');
      }
}
