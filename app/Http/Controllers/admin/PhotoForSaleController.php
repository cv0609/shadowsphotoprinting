<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoForSale;
use App\Http\Requests\PhotoForSaleCategoryRequest;

class PhotoForSaleController extends Controller
{
    public function productCategory()
    {
       $categories = PhotoForSale::get();
       return view('admin.photo_for_sale.category.index',compact('categories'));
    }

    public function productCategoryAdd()
    {
        return view('admin.photo_for_sale.category.add');
    }

    public function productCategorySave(PhotoForSaleCategoryRequest $request)
    {
        $slug = \Str::slug($request->name);
        $image = "";
        if($request->has('image'))
         {
             $file = $request->file('image');
             $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
             $destinationPath = 'assets/admin/uploads/categories';
             $file->move($destinationPath, $fileName);
             $image =  $destinationPath.'/'.$fileName;
         }
         PhotoForSale::insert(["name"=>$request->name,'slug'=>$slug,'image'=>$image]);
        return redirect()->route('photos-for-sale-categories-list')->with('success','Category inserted successfully');
    }

    public function productCategoryShow($category_id)
    {
        $category = PhotoForSale::whereId($category_id)->first();
        return view('admin.photo_for_sale.category.edit', compact('category'));
    }

    public function productCategoryDistroy($category_id)
    {
       $category = PhotoForSale::whereId($category_id)->delete();
       return redirect()->route('photos-for-sale-categories-list')->with('success','Category is deleted successfully');
    }


}
