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
use Illuminate\Support\Facades\Storage;
use App\Services\CartService;
use App\Mail\MakeOrder;
use Illuminate\Support\Facades\Mail;
use App\Models\TestPrint;
use App\Models\Country;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use App\Models\CartData;
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
    $currentDate = date('F-j-Y-1');
    $page_content = [
        "meta_title" => "{$currentDate} - " . config('constant.pages_meta.shop_detail.meta_title'),
        "meta_description" => config('constant.pages_meta.shop_detail.meta_description')
    ];

    $countries = Country::whereIn('code', ['AU', 'NZ'])->get();

    if (Auth::check() && !empty(Auth::user())) {
      $user = Auth::user();
      $auth_id = $user->id;
      $cart = Cart::where('user_id', $auth_id)->with('items.product')->first();
    } else {
        // Guest user logic with referral_code already in session
        $session_id = Session::getId();
        $cart = Cart::where('session_id', $session_id)->with('items.product')->first();
    }

    return view('front-end/shop_detail', compact('imageName','products','productCategories','page_content','cart','countries'));
  }  

  public function getProductsBycategory(Request $request)
  {
    $categorySlug = $request->slug;
    $products = [];
    $country = $request->country;

    
    // Handle wedding package category specially
    if($categorySlug == 'wedding-package') {
        // Don't load any products, just show a message to select a wedding package
        echo '<tr><td colspan="4" style="text-align: center; padding: 20px;">Please select a wedding package from the dropdown above to view available products.</td></tr>';
        return;
    }
    
    if($categorySlug == "all")
    {
      $products = Product::where('category_id','!=',20)->select(['id','product_title','product_price'])->orderBy('position','asc')->get();
    } elseif($country == "New Zealand") {

    $categories = ProductCategory::whereIn('name', [
        'Photo Prints',
        'scrapbook page printing',
        'canvas prints'
    ])->get()->keyBy('name');

    $photoPrintId = optional($categories->get('Photo Prints'))->id;
    $scrapbookId = optional($categories->get('scrapbook page printing'))->id;
    $canvasId = optional($categories->get('canvas prints'))->id;

    $allowedCanvasSizes = [
        '12-x12','12-x16','12-x20','12-x30','12x40','16-x16','16-x20','16-x30','20-x20','20-x30',
        '20-x40','30-x20','30-x30','30-x40','40-x30'
    ];

    $products = Product::query();

    // Category selected
    if (!empty($categorySlug) && $categorySlug != 'all') {

        $category = ProductCategory::where('slug', $categorySlug)->first();

        Log::info('NZ Category Selected', [
            'slug' => $categorySlug,
            'category' => $category ? $category->name : null
        ]);

        if ($category) {

            // Photo Prints
           if ($category->id == $photoPrintId) {

                $products->where('category_id', $photoPrintId)
                        ->where('slug', 'NOT LIKE', '%12-x-48%');
            }

            // Scrapbook Page Printing
            elseif ($category->id == $scrapbookId) {

                $products->where('category_id', $scrapbookId);
            }

            // Canvas Prints
            elseif ($category->id == $canvasId) {

                $products->where('category_id', $canvasId)
                    ->where(function ($query) use ($allowedCanvasSizes) {

                        foreach ($allowedCanvasSizes as $size) {
                            $query->orWhere('slug', 'like', '%' . $size . '%');
                        }
                    });
            }
            // Any other category not allowed for NZ
            else {

                $products->whereRaw('1 = 0');
            }
        }

    } else {

        // No category selected -> show all allowed NZ products
        $products->where(function ($query) use (
            $photoPrintId,
            $scrapbookId,
            $canvasId,
            $allowedCanvasSizes
        ) {

              // Photo Prints
            // Photo Prints
              $query->orWhere(function ($q) use ($photoPrintId) {
                  $q->where('category_id', $photoPrintId)
                    ->where('slug', 'NOT LIKE', '%12-x-48%');
              });

              // Scrapbook
              $query->orWhere('category_id', $scrapbookId);

              // Canvas
              $query->orWhere(function ($q) use ($canvasId, $allowedCanvasSizes) {

                  $q->where('category_id', $canvasId)
                      ->where(function ($canvas) use ($allowedCanvasSizes) {

                          foreach ($allowedCanvasSizes as $size) {
                              $canvas->orWhere('slug', 'like', '%' . $size . '%');
                          }
                      });
                  });

              });
          }

        $products = $products
            ->select([
                'id',
                'product_title',
                'product_price',
                'slug',
                'category_id'
            ])
            ->orderBy('position', 'asc')
            ->get();

        return response()->json([
            'products' => view(
                'front-end.shop_details_product_ajax',
                compact('products')
            )->render(),
            'categories' => $categories->values()
        ]);
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

 public function checkCartCountry(Request $request)
  {
      Log::info('Checking cart country', [
          'user_id' => auth()->id(),
          'session_id' => session()->getId(),
          'country_id' => $request->country_id
      ]);

      $cart = Cart::where(function ($query) {
          if (auth()->check()) {
              $query->where('user_id', auth()->id());
          } else {
              $query->where('session_id', session()->getId());
          }
      })->first();

      // No cart
      if (!$cart) {
          return response()->json([
              'hasCart' => false
          ]);
      }

      // Cart exists but has no items
      $hasItems = CartData::where('cart_id', $cart->id)->exists();

      if (!$hasItems) {
          return response()->json([
              'hasCart' => false
          ]);
      }

      Log::info('Cart found', [
          'cart_id' => $cart->id,
          'cart_country_id' => $cart->country_id
      ]);

      // Country not set yet
      if (empty($cart->country_id)) {

          $cart->country_id = $request->country_id;
          $cart->save();

          return response()->json([
              'hasCart' => true,
              'sameCountry' => true
          ]);
      }

      // Same country
      if ($cart->country_id == $request->country_id) {

          return response()->json([
              'hasCart' => true,
              'sameCountry' => true
          ]);
      }

      // Different country
      return response()->json([
          'hasCart' => true,
          'sameCountry' => false
      ]);
  }

  public function changeCartCountry(Request $request)
  {
      $cart = Cart::where(function ($query) {
          if (auth()->check()) {
              $query->where('user_id', auth()->id());
          } else {
              $query->where('session_id', session()->getId());
          }
      })->first();

      if (!$cart) {
          return response()->json([
              'success' => false
          ]);
      }

      // Delete existing cart items
      CartData::where('cart_id', $cart->id)->delete();

      // Update cart country
      $cart->country_id = $request->country_id;
      $cart->save();

      return response()->json([
          'success' => true
      ]);
  }

  public function getCartCountry()
  {

      $cart = Cart::where(function ($query) {
          if (auth()->check()) {
              $query->where('user_id', auth()->id());
          } else {
              $query->where('session_id', session()->getId());
          }
      })->first();

      return response()->json([
          'country_id' => $cart->country_id ?? null
      ]);
  }



}
 