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
use App\Mail\MakeOrder;
use Illuminate\Support\Facades\Mail;

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
        if($request->allimages)
        {
            foreach($request->allimages as $key => $image)
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

    $imageName = Session::get('temImages'); 
    
    if(isset($category_slug) && $category_slug != null)
    {
      $productCategories = ProductCategory::where('slug','!=','photos-for-sale')->where('slug','!=','gift-card')->where('slug','!=','hand-craft')->get();
    }
    else
    {
      $productCategories = ProductCategory::where('slug','!=','photos-for-sale')->where('slug','!=','gift-card')->where('slug','!=','hand-craft')->get();
    }
    
    $products = Product::select(['id','product_title','product_price'])->get();
    $currentDate = date('F-j-Y-1');
    $page_content = [
        "meta_title" => "{$currentDate} - " . config('constant.pages_meta.shop_detail.meta_title'),
        "meta_description" => config('constant.pages_meta.shop_detail.meta_description')
    ];

    return view('front-end/shop_detail', compact('imageName','products','productCategories','page_content'));
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