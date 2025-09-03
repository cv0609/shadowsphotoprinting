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
      'images' => 'required|array',
      'images.*' => 'url'
    ], [
      'images.required' => 'No images were uploaded.',
      'images.array' => 'The images must be provided as an array.',
      'images.*.url' => 'Each image must be a valid URL.',
    ]);

    $temImagesStore = Session::get('temImages', []);

    $imageUrls = $request->input('images');

    foreach ($imageUrls as $imageUrl) {
      $temImagesStore[] = $imageUrl;
    }

    Session::put('temImages', $temImagesStore);

    return response()->json([
      'csrf_token' => csrf_token(),
    ]);
  }    

  public function uploadImageCsrfRefresh()
  {
    return json_encode(['csrf_token' => csrf_token()]);
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
    
    // Handle wedding package category specially
    if($categorySlug == 'wedding-package') {
        // Don't load any products, just show a message to select a wedding package
        echo '<tr><td colspan="4" style="text-align: center; padding: 20px;">Please select a wedding package from the dropdown above to view available products.</td></tr>';
        return;
    }
    
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

        $products = Product::whereIn('id', $productIds)->orderBy('position', 'asc')->get();

        foreach ($products as $product) {
          $testPrintData = $testPrintCollection->filter(function ($testPrint) use ($product) {
              return in_array($product->id, explode(',', $testPrint->product_id));
          })->values(); 
          $product->test_print = $testPrintData;
        }
      }else{
        $category =  ProductCategory::where('slug', $categorySlug)->first();
        // $products = $category->products;
        $products = $category->products()->orderBy('position', 'asc')->get();
      }
    }
    echo view('front-end/shop_details_product_ajax', compact('products'));
  }

  public function getWeddingPackagesList()
  {
    // Get wedding package products from database (category ID 20)
    $category = ProductCategory::where('id', 20)->first();
    if($category) {
        $products = $category->products()->orderBy('position', 'asc')->get();
        
        // Format products for dropdown
        $packages = $products->map(function($product) {
            return [
                'name' => $product->product_title,
                'slug' => $product->slug,
                'price' => $product->product_price
            ];
        });
        return response()->json($packages);
    }

    
    return response()->json([]);
  }

  public function getWeddingPackageFrames(Request $request)
  {
    $packageSlug = $request->input('package_slug');

    $package_product = Product::where('slug',$packageSlug)->first();
    
    // Load wedding package data from JSON
    $jsonPath = resource_path('pages_json/wedding_packages.json');
    if(!file_exists($jsonPath)) {
        echo '<tr><td colspan="4" style="text-align: center; padding: 20px;">Wedding package data not found</td></tr>';
        return;
    }
    
    $weddingPackageData = json_decode(file_get_contents($jsonPath), true);
    
    // Find the selected package in JSON
    $selectedPackage = null;
    foreach($weddingPackageData['packages'] as $package) {
        if($package['slug'] === $packageSlug) {
            $selectedPackage = $package;
            break;
        }
    }
    
    if(!$selectedPackage) {
        echo '<tr><td colspan="4" style="text-align: center; padding: 20px;">Package not found</td></tr>';
        return;
    }
    
    $matchingProducts = collect();
    
    // For each frame in the JSON package, find matching products by category_id and slug
    foreach($selectedPackage['frames'] as $frame) {
        $categoryId = $frame['category_id'];
        $frameSlug = $frame['slug'];
        
        // Get the category from database
        $category = ProductCategory::where('id', $categoryId)->first();
        if(!$category) {
            continue; // Skip if category not found
        }
        
        // Find products that match the slug in the specific category
        $matchingFrameProducts = $category->products()
            ->where('slug', $frameSlug)
            ->orderBy('position', 'asc')
            ->get();  
        
        // Add matching products to the collection
        foreach($matchingFrameProducts as $product) {
            // Add frame data to the product for display
            // $product->frame_data = $frame;
            $product->is_package = 1;
            $product->package_price = $package_product->product_price;
            $product->package_product_id = $package_product->id;
            $matchingProducts->push($product);
        }
    }

    // \Log::info('$matchingProducts');
    // \Log::info($matchingProducts);
    
    // Use the same view as regular products
    $products = $matchingProducts;
    echo view('front-end/shop_details_product_ajax', compact('products'));
  }

}