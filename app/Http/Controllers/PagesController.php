<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Blog;
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

        return view($slug,compact('page_content','page_info'));
      }
      else
      {
        abort(404);
      }
  }

  public function blogDetail($slug)
  {
      $blog_details = Blog::where('slug',$slug)->first();
      return view('blog_detail',compact('blog_details'));
  }

  public function PhotosForSale()
  {
    return view('photos-for-sale');
  }
}
