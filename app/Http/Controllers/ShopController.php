<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
   public function uploadImage(Request $request)
    {
        $temImagesStore = [];
        if($request->image)
        {
            foreach($request->image as $key => $image)
            {
                $temImages = time().$key.'.'.$image->extension();

                // Store image in the temporary directory
                $image->storeAs('public/temp', $temImages);
                $temImagesStore[] = $temImages;
            }
            
        }
        // Pass the image name to the display route
        return redirect()->route('shop-detail')->with('temImages', $temImagesStore);
    }
  public function shopDetail($category_slug = null)
  {
    $imageName = session('temImages'); 
    if(isset($category_slug) && $category_slug != null)
    {
      $productCategories = ProductCategory::get();
    }
    else
    {
      $productCategories = ProductCategory::get();
    }
    $productCategories = ProductCategory::get();
    $products = Product::select(['id','product_title','product_price'])->get();
    return view('front-end/shop_detail', compact('imageName','products','productCategories'));
  }  

  public function getProductsBycategory(Request $request)
  {
    $categorySlug = $request->slug;
    $products = [];
    if($categorySlug == "all")
    {
      $products = Product::select(['id','product_title','product_price'])->get();
    }
    else
    {
      $category =  ProductCategory::where('slug', $categorySlug)->first();
      $products = $category->products;
    }
    echo view('front-end/shop_details_product_ajax', compact('products'));
  }
}