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

       $orderDetail = Order::whereId(36)->with('orderDetails.product','OrderBillingDetail')->first();
      // $orderDetail['domain']='http://127.0.0.1:8000';
      
      // // $orderDetail = $order->whereId($order->id)->with('orderDetails','OrderBillingDetail')->first();

      // return view('mail.make-order',compact('order'));

      // $orderDetail

      Mail::to('sandeep1avology@gmail.com')->send(new MakeOrder($orderDetail));

      dd('succes');

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