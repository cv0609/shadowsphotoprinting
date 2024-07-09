<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;
use App\Models\SizeType;
use App\Http\Requests\SizeRequest;
use App\Http\Requests\SizetypeRequest;

class VariationsController extends Controller
{
   public function sizes()
    {
      $sizes = Size::paginate();
      return view('admin.variations.sizes.index',compact('sizes'));
    }

   public function addSize()
     {
        return view('admin.variations.sizes.add');
     }

    public function saveSize(SizeRequest $request)
     {
        Size::insert(['name'=>$request->name]);
        return redirect()->route('sizes-list')->with('success','Size added successfully');
     }

    public function deleteSize($id)
     {
        Size::whereId($id)->delete();
        return redirect()->route('sizes-list')->with('success','Size type deleted successfully');
     }

     public function sizesType()
     {
       $SizeTypes = SizeType::paginate();
       return view('admin.variations.size_type.index',compact('SizeTypes'));
     }

    public function addSizeType()
      {
         return view('admin.variations.size_type.add');
      }

     public function saveSizeType(sizetypeRequest $request)
      {
        SizeType::insert(['name'=>$request->name]);
        return redirect()->route('size-types-list')->with('success','Size type added successfully');
      }

    public function deleteSizeType($id)
      {
        SizeType::whereId($id)->delete();
        return redirect()->route('size-types-list')->with('success','Size type deleted successfully');
      }

}
