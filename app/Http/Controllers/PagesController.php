<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Blog;
use App\Models\GiftCardCategory;
use App\Models\PhotoForSaleCategory;
use App\Models\PhotoForSaleProduct;
use App\Services\PageDataService;

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
        $slug = end($segments);

        // Default to 'home' if the slug is empty
        if (empty($slug)) {
            $slug = 'home';
        }

      $page_info = Page::where('slug',$slug)->with('pageSections')->first();
      if($page_info && isset($page_info->pageSections) && !empty($page_info->pageSections))
      {
        $page_content = json_decode($page_info->pageSections['content'],true);
        $page_content['slug'] = $page_info['slug'];

        return view('front-end/category_detail',compact('page_content','page_info'));
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
        return view('front-end/blog_detail',compact('blog_details','previousBlog','nextBlog'));
  }

  public function PhotosForSale($slug = null)
  {
    if($slug == null)
     {
       $products = PhotoForSaleProduct::paginate(10);
     }
    else
     {
        $caregory = PhotoForSaleCategory::where('slug',$slug)->first();
        $products = PhotoForSaleProduct::where('category_id',$caregory->id)->paginate(10);
    }
    $productCategories = PhotoForSaleCategory::get();
    return view('front-end/photos-for-sale',compact('products','productCategories'));
  }


  public function PhotoForSaleByCategory($slug)
   {

   }
  public function giftCard($slug = null)
  {
     $cards =  GiftCardCategory::get();
     return view('front-end/giftcard',compact('cards'));
   
  }

}
