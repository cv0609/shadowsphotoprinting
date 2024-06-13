<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoForSaleCategory;
use App\Models\PhotoForSaleProduct;
use App\Http\Requests\PhotoForSaleCategoryRequest;
use App\Http\Requests\PhotoForSaleProductRequest;

class PhotoForSaleController extends Controller
{
    public function productCategory()
    {
       $categories = PhotoForSaleCategory::get();
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
         PhotoForSaleCategory::insert(["name"=>$request->name,'slug'=>$slug,'image'=>$image]);
        return redirect()->route('photos-for-sale-categories-list')->with('success','Category inserted successfully');
    }

    public function productCategoryShow($category_id)
    {
        $category = PhotoForSaleCategory::whereId($category_id)->first();
        return view('admin.photo_for_sale.category.edit', compact('category'));
    }

    public function productCategoryUpdate(Request $request)
    {

        $slug = \Str::slug($request->name);
        $data = ["name"=>$request->name,'slug'=>$slug];
        if($request->has('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/admin/uploads/categories';
            $file->move($destinationPath, $fileName);
            $image =  $destinationPath.'/'.$fileName;
            $data['image']=$image;
        }
        PhotoForSaleCategory::whereId($request->category_id)->update($data);

        return redirect()->route('photos-for-sale-categories-list')->with('success','Category updated successfully');
    }

    public function productCategoryDistroy($category_id)
    {
       $category = PhotoForSaleCategory::whereId($category_id)->delete();
       return redirect()->route('photos-for-sale-categories-list')->with('success','Category is deleted successfully');
    }


    public function products()
    {
        $products = PhotoForSaleProduct::with(['product_category' => function($query) {
            $query->select('id', 'name');
        }])->paginate(10);
        return view('admin.photo_for_sale.index', compact('products'));
    }

    public function productAdd()
    {

        $productCategories = PhotoForSaleCategory::get();
        return view('admin.photo_for_sale.add',compact('productCategories'));
    }

    public function productSave(PhotoForSaleProductRequest $request)
    {
        $slug = \Str::slug($request->product_title);

        $data = ["category_id"=>$request->category_id,"product_title"=>preg_replace('/[^\w\s]/',' ', $request->product_title),"product_description"=>$request->product_description,"min_price"=>$request->min_price,"max_price"=>$request->min_price,'slug'=>$slug];

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('assets/admin/images'), $imageName);
            $product_image = 'assets/admin/images/'.$imageName;
            $product_image_array[] = $product_image;
            }
            $data["product_images"] = implode(',',$product_image_array);
         }
         PhotoForSaleProduct::insert($data);
        return redirect()->route('photos-for-sale-product-list')->with('success','Product inserted successfully');
    }

    public function productShow($slug)
    {
        $product = PhotoForSaleProduct::where('slug', $slug)->first();
        $productCategories = PhotoForSaleCategory::get();
        return view('admin.photo_for_sale.edit', compact('product','productCategories'));
    }

    public function productUpdate(Request $request)
    {
        $slug = \Str::slug($request->product_title);

        $data = ["category_id"=>$request->category_id,"product_title"=>preg_replace('/[^\w\s]/',' ', $request->product_title),"product_description"=>$request->product_description,"min_price"=>$request->min_price,"max_price"=>$request->max_price,'slug'=>$slug];

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('assets/admin/images'), $imageName);
            $product_image = 'assets/admin/images/'.$imageName;
            $product_image_array[] = $product_image;
            }
            $data["product_images"] = implode(',',$product_image_array);
         }

        PhotoForSaleProduct::whereId($request->product_id)->update($data);
        return redirect()->route('photos-for-sale-product-list')->with('success','Product updated successfully');
    }


   public function productDistroy($product_id)
    {
       $category = PhotoForSaleProduct::whereId($product_id)->delete();
       return redirect()->route('photos-for-sale-product-list')->with('success','Product is deleted successfully');
    }

}
