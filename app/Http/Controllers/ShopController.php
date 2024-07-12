<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderBillingDetails;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;
use App\Services\CartService;

use Session;
class ShopController extends Controller
{

    protected $CartService;

    public function __construct(CartService $CartService)
    {
        $this->CartService = $CartService;
    }

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
        Session::put('temImages',$temImagesStore);
        return redirect()->route('shop-detail');
    }
  public function shopDetail($category_slug = null)
  {

    // $orderDetail = Order::whereId(40)->with('orderDetails.product','OrderBillingDetail')->first();

    // foreach($orderDetail->orderDetails as $details){
    //   if($details->product_type == 'photo_for_sale'){

    //     echo $details->product_type;
          
    //   }elseif($details->product_type == 'gift_card'){
    //     echo $details->product_type;
    //   }else{
    //      echo "no";
    //   }
    //   // $this->CartService->getProductDetailsByType($details->product_id,$details->product_type);
    // }

  

    $imageName = Session::get('temImages'); 
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