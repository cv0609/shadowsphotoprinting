<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Blog;
use App\Models\GiftCardCategory;
use App\Models\PhotoForSaleCategory;
use App\Models\PhotoForSaleProduct;
use App\Models\HandCraftCategory;
use App\Models\HandCraftProduct;
use App\Models\PhotoForSaleSizePrices;
use App\Services\PageDataService;
use App\Http\Requests\GetAQuoteRequest;
use App\Mail\QuoteMail;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\FuncCall;
use App\Models\ProductCategory;

class PagesController extends Controller
{
  private $PageDataServices;

  public function __construct(PageDataService $PageDataService)
  {
    $this->PageDataServices = $PageDataService;
  }
  public function pages(Request $request)
  {
    // Get the full path
        $path = $request->path();

        // Extract the last segment
        $segments = explode('/', $path);
        if(in_array('our-products',$segments) && end($segments) != 'our-products')
          {
            $slug = ProductCategory::where('name',str_replace('-',' ',end($segments)))->select('slug')->first();

            $slug = $slug['slug'];

          }
        else
         {
            $slug = end($segments);

         }

        // Default to 'home' if the slug is empty
        if (empty($slug)) {
            $slug = 'home';
        }
      $page_info = Page::where('slug',$slug)->with('pageSections')->first();

      if($page_info && isset($page_info->pageSections) && !empty($page_info->pageSections))
      {
        $page_content = json_decode($page_info->pageSections['content'],true);
        $page_content['slug'] = $page_info['slug'];

        if($page_info['is_product_page'] == '1'){
             return view('front-end/common-product',compact('page_content','page_info'));
        }else{
          return view('front-end/'.$slug,compact('page_content','page_info'));
        }
      }
      else
      {
        abort(404);
      }
  }

  public function blogDetail($slug)
  {
    $blog_details = Blog::where('slug',$slug)->first();

    $previousBlog = Blog::where('id', '<', $blog_details->id)
      ->orderBy('id', 'desc')
      ->first();

    $nextBlog = Blog::where('id', '>', $blog_details->id)
      ->orderBy('id', 'asc')
      ->first();

      $page_content = ["meta_title"=>$blog_details['slug'],"meta_description"=>$blog_details['description']];
    return view('front-end/blog_detail',compact('blog_details','previousBlog','nextBlog','page_content'));
  }

  public function PhotosForSale($slug = null)
  {
    $page_content = [];
    if($slug == null)
    {
      $products = PhotoForSaleProduct::paginate(10);
      $page_content = ["meta_title"=>config('constant.pages_meta.photos_for_sale.meta_title'),"meta_description"=>config('constant.pages_meta.photos_for_sale.meta_description')];
    }
    else
    {
      $caregory = PhotoForSaleCategory::where('slug',$slug)->first();
      $products = PhotoForSaleProduct::where('category_id',$caregory->id)->paginate(10);
      $page_content = ["meta_title"=>$caregory['slug'],"meta_description"=>$caregory['name']];

    }


    $productCategories = PhotoForSaleCategory::get();


    return view('front-end/photos-for-sale',compact('products','productCategories','page_content'));
  }

  public function PhotosForSaleDetails($slug = null){
    $productDetails = PhotoForSaleProduct::where('slug',$slug)->first();

    // Get unique size_id records with their size names for the given product_id
    $uniqueSizeRecords = PhotoForSaleSizePrices::with('getSizeById')->select('size_id')
            ->where('product_id', $productDetails->id)
            ->groupBy('size_id')
            ->get();

    $uniqueTyepeRecords = PhotoForSaleSizePrices::with('getTypeById')->select('type_id')
            ->where('product_id', $productDetails->id)
            ->groupBy('type_id')
            ->get();

    $photoForSaleSizePricesData = PhotoForSaleSizePrices::where('product_id',$productDetails->id)->get();

    $relatedProduct = PhotoForSaleProduct::where('slug','!=',$slug)->paginate(10);

    $page_content = ["meta_title"=>$productDetails['slug'],"meta_description"=>$productDetails['product_description']];

    return view('front-end/photos-for-sale-details',compact('productDetails','relatedProduct','uniqueSizeRecords','uniqueTyepeRecords','photoForSaleSizePricesData','page_content'));
  }


  public function handCraftDetails($slug = null){

    $productDetails = HandCraftProduct::where('slug',$slug)->first();
    $relatedProduct = HandCraftProduct::where('slug','!=',$slug)->paginate(10);
    $page_content = ["meta_title"=>$productDetails['slug'],"meta_description"=>$productDetails['product_description']];
    return view('front-end/hand-craft-details',compact('productDetails','relatedProduct','page_content'));
  }


  public function PhotoForSaleByCategory($slug)
  {

  }

  public function giftCard()
  {
    $blogs =  GiftCardCategory::get();
    $page_content = ["meta_title"=>config('constant.pages_meta.gift_card.meta_title'),"meta_description"=>config('constant.pages_meta.gift_card.meta_description')];

    return view('front-end/giftcard',compact('blogs','page_content'));
  }

  public function handCraft($slug = null)
  {
    $page_content = [];
    if($slug == null)
    {
      $products = HandCraftProduct::paginate(10);
      $page_content = ["meta_title"=>config('constant.pages_meta.hand_craft.meta_title'),"meta_description"=>config('constant.pages_meta.hand_craft.meta_description')];
    }
    else
    {
      $caregory = HandCraftCategory::where('slug',$slug)->first();
      $products = HandCraftProduct::where('category_id',$caregory->id)->paginate(10);
      $page_content = ["meta_title"=>$caregory['slug'],"meta_description"=>$caregory['name']];

    }


    $productCategories = HandCraftCategory::get();


    return view('front-end/hand-craft',compact('products','productCategories','page_content'));
  }


  public function giftCard_detail($slug)
  {
    $blog_detail = GiftCardCategory::where(["slug"=>$slug])->first();
    $related_products =  GiftCardCategory::where("slug","!=",$slug)->get();
    $page_content = ["meta_title"=>$blog_detail['slug'],"meta_description"=>$blog_detail['product_description']];

    return view('front-end/giftcard_detail',compact('blog_detail','related_products','page_content'));
  }

  public function sendQuote(GetAQuoteRequest $request){
      $email = $request->email;

      $data['name'] = $request->name;
      $data['last_name'] = $request->last_name;
      $data['phone_number'] = $request->phone_number;
      $data['requested'] = $request->requested;
      $data['message'] = $request->message;
      $data['email'] = $request->email;

      if(isset($data) && !empty($data)){
        Mail::to(env('APP_MAIL'))->send(new QuoteMail($data));
      }
      return redirect()->back()->with('success', 'Quote message sent successfully.');
  }

  // public function giftcardSearch(Request $request){
  //    dd($request->all());
  // }
}
