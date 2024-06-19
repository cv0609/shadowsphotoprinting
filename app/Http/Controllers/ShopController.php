<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
   public function uploadImage(Request $request)
    {
        $temImagesStore = [];
        if($request->image)
        {
            foreach($request->image as $image)
            {
                $temImages = time().'.'.$image->extension();

                // Store image in the temporary directory
                $image->storeAs('public/temp', $temImages);
                $temImagesStore[] = $temImages;
            }
            
        }
       
        // Pass the image name to the display route
        return redirect()->route('shop-detail')->with('temImages', $temImagesStore);
    }
  public function shopDetail()
  {
    $imageName = session('temImages'); 
    $products = Product::select(['id','product_title','product_price'])->get();
    return view('front-end/shop_detail', compact('imageName','products'));
  }  
}
