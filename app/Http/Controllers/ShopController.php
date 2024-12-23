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
use App\Models\TestPrint;
use Intervention\Image\Facades\Image;
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
    $this->validate($request, [
      'image.*' => 'required|file|max:204800', // Validate each image (1 MB = 1024 KB)
      ], [
          'image.*.required' => 'Please upload an image.',
          'image.*.file' => 'The uploaded file must be a valid file.',
          'image.*.max' => 'Image size must not exceed 1 MB to upload.',
      ]
    );

    // Retrieve existing images from the session, or initialize an empty array
    if ($request->input('is_first_upload')) {
        $temImagesStore = [];
    } else {
        $temImagesStore = Session::get('temImages', []);
    }

    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $originalImageName = time() . '.' . $image->extension();

      $image->storeAs('public/temp', $originalImageName);

      // Create a resized version of the image (150x150)
      $resizedImageName = 'thumb_' . $originalImageName;
      $resizedImage = Image::make($image)->resize(200, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });
      
      // Store the resized image in the temporary directory
      $imageContent = $resizedImage->stream()->__toString();
      Storage::disk('public')->put('temp/' . $resizedImageName, $imageContent);

      $temImagesStore[] = $originalImageName;
    }

    // Save the updated session data
    Session::put('temImages', $temImagesStore);

    return response()->json(['csrf_token' => csrf_token()]);
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
    // dd($productCategories);
    $products = Product::select(['id','product_title','product_price'])->orderBy('position','asc')->get();
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
      $products = Product::select(['id','product_title','product_price'])->orderBy('position','asc')->get();
    }
    else
    {
      if($categorySlug == 'test-print'){  
        
        $testPrintCollection = TestPrint::all();
        $productIds = [];

        foreach ($testPrintCollection as $testPrint) {
          $ids = explode(',', $testPrint->product_id);
          $productIds = array_merge($productIds, $ids);
        }

        $productIds = array_unique($productIds);

        $products = Product::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
          $testPrintData = $testPrintCollection->filter(function ($testPrint) use ($product) {
              return in_array($product->id, explode(',', $testPrint->product_id));
          })->values(); 
          $product->test_print = $testPrintData;
        }
      }else{
        $category =  ProductCategory::where('slug', $categorySlug)->first();
        $products = $category->products;
      }
    }
    echo view('front-end/shop_details_product_ajax', compact('products'));
  }
}