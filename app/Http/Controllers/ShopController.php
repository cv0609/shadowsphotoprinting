<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderBillingDetails;
use App\Models\ProductCategory;
use App\Models\Country;
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
    // Generate timestamp with milliseconds for better uniqueness
    $microtime = microtime(true); // Get microseconds as float
    $milliseconds = str_pad((int)(($microtime - floor($microtime)) * 1000), 3, '0', STR_PAD_LEFT); // Extract and pad milliseconds
    $timeString = date('Y-m-d-H-i-s') . '-' . $milliseconds . '-' . (int)$microtime;

    foreach ($imageUrls as $imageUrl) {
      // Extract filename from URL and add timestamp prefix if not already present
      $parsedUrl = parse_url($imageUrl);
      $path = $parsedUrl['path'] ?? '';
      $pathParts = explode('/', $path);
      $fileName = end($pathParts);
      
      // Check if timestamp already exists in filename (format: Y-m-d-H-i-s-milliseconds-timestamp-filename)
      $timestampPattern = '/^\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}-\d{3}-\d+-/';
      if (!preg_match($timestampPattern, $fileName)) {
        // Add timestamp prefix to filename
        $newFileName = $timeString . '-' . $fileName;
        // Reconstruct URL with new filename
        $pathParts[count($pathParts) - 1] = $newFileName;
        $newPath = implode('/', $pathParts);
        $imageUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $newPath;
      }
      
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
    $products = Product::where('category_id','!=',20)->select(['id','product_title','product_price'])->orderBy('position','asc')->get();

    $shippingCountry = Session::get('shop_shipping_country', 'AU');
    if (!in_array($shippingCountry, ['AU', 'NZ'], true)) {
      $shippingCountry = 'AU';
      Session::put('shop_shipping_country', $shippingCountry);
    }

    if ($this->isNewZealand()) {
      $productCategories = ProductCategory::whereIn('id', config('nz_catalog.allowed_category_ids'))->get();
      $products = $this->filterProductsForNewZealand($products);
    }

    $shippingCountries = Country::whereIn('code', ['AU', 'NZ'])
      ->orderByRaw("FIELD(code, 'AU', 'NZ')")
      ->get(['name', 'code']);
    $currentDate = date('F-j-Y-1');
    $page_content = [
        "meta_title" => "{$currentDate} - " . config('constant.pages_meta.shop_detail.meta_title'),
        "meta_description" => config('constant.pages_meta.shop_detail.meta_description')
    ];

    if (Auth::check() && !empty(Auth::user())) {
      $user = Auth::user();
      $auth_id = $user->id;
      $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
    } else {
        // Guest user logic with referral_code already in session
        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
    }

    return view('front-end/shop_detail', compact('imageName','products','productCategories','page_content','cart','shippingCountries','shippingCountry'));
  }  

  public function setShopShippingCountry(Request $request)
  {
    $request->validate([
      'country_code' => 'required|string|size:2',
    ]);

    $country = Country::whereIn('code', ['AU', 'NZ'])
      ->where('code', strtoupper($request->country_code))
      ->firstOrFail();

    Session::put('shop_shipping_country', $country->code);

    $cart = Auth::check()
      ? Cart::where('user_id', Auth::id())->whereNull('session_id')->first()
      : Cart::where('session_id', Session::getId())->first();

    // An empty cart can follow the newly selected country. A cart containing
    // products keeps its original country until the user clears it.
    if ($cart && !$cart->items()->exists()) {
      $cart->update(['shipping_country_id' => $country->id]);
    }

    if ($country->code === 'NZ') {
      $categories = ProductCategory::whereIn('id', config('nz_catalog.allowed_category_ids'))->get();
    } else {
      $categories = ProductCategory::where('slug','!=','photos-for-sale')
        ->where('slug','!=','gift-card')
        ->where('slug','!=','hand-craft')
        ->get();
    }

    return response()->json([
      'country_code' => $country->code,
      'categories' => $categories->map(function ($category) {
        return [
          'name' => ucfirst($category->name),
          'slug' => $category->slug,
        ];
      })->values(),
    ]);
  }

  public function getProductsBycategory(Request $request)
  {
    $categorySlug = $request->slug;
    $products = [];

    if ($this->isNewZealand() && $categorySlug !== 'all'
        && !in_array($categorySlug, config('nz_catalog.allowed_category_slugs'), true)) {
      echo '<tr><td colspan="4" style="text-align: center; padding: 20px;">This category is not available for New Zealand.</td></tr>';
      return;
    }
    
    // Handle wedding package category specially
    if($categorySlug == 'wedding-package') {
        // Don't load any products, just show a message to select a wedding package
        echo '<tr><td colspan="4" style="text-align: center; padding: 20px;">Please select a wedding package from the dropdown above to view available products.</td></tr>';
        return;
    }
    
    if($categorySlug == "all")
    {
      $products = Product::where('category_id','!=',20)->select(['id','product_title','product_price'])->orderBy('position','asc')->get();
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
    if ($this->isNewZealand()) {
      $products = $this->filterProductsForNewZealand(collect($products));
    }

    echo view('front-end/shop_details_product_ajax', compact('products'));
  }

  private function isNewZealand()
  {
    return Session::get('shop_shipping_country', 'AU') === 'NZ';
  }

  private function filterProductsForNewZealand($products)
  {
    $blockedProductIds = array_merge(
      config('nz_catalog.blocked_photo_print_ids'),
      config('nz_catalog.blocked_photo_enlargement_ids'),
      config('nz_catalog.blocked_poster_ids')
    );

    $allowedProductsQuery = Product::whereIn('category_id', config('nz_catalog.allowed_category_ids'))
      ->whereNotIn('id', $blockedProductIds)
      ->where(function ($query) {
        $query->where('category_id', '!=', 2)
          ->orWhereIn('id', config('nz_catalog.allowed_canvas_product_ids'));
      });

    foreach (config('nz_catalog.blocked_product_title_patterns') as $pattern) {
      $allowedProductsQuery->where('product_title', 'not like', '%' . $pattern . '%');
    }

    $allowedProductIds = $allowedProductsQuery->pluck('id');

    return collect($products)->whereIn('id', $allowedProductIds)->values();
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
              $product->package_slug = $packageSlug; // Add package slug
              $matchingProducts->push($product);
        }
    }

    // \Log::info('$matchingProducts');
    // \Log::info($matchingProducts);
    
    // Use the same view as regular products
    $products = $matchingProducts;
    echo view('front-end/shop_details_product_ajax', compact('products'));
  }

  public function getWeddingPackagesJson()
  {
    $jsonPath = resource_path('pages_json/wedding_packages.json');
    
    if (!file_exists($jsonPath)) {
      return response()->json(['error' => 'Wedding packages file not found'], 404);
    }
    
    $jsonContent = file_get_contents($jsonPath);
    $data = json_decode($jsonContent, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
      return response()->json(['error' => 'Invalid JSON format'], 500);
    }
    
    return response()->json($data);
  }

}