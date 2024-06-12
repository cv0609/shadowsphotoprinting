<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Services\PageDataService;

class PagesController extends Controller
{
  private $PageDataServices;

  public function __construct(PageDataService $PageDataService)
  {
    $this->PageDataServices = $PageDataService;
  }
  public function pages($slug = 'home')
  {
    $content = Page::where('slug',$slug)->with('pageSections')->first();
    if($content && isset($content->pageSections) && !empty($content->pageSections))
    {
      $page_content = json_decode($content->pageSections['content'],true);
      return view($slug,compact('page_content'));
    }
    else
    {
      abort(404);
    }
  }

  public function ourProducts($slug = '')
  {
      if(isset($slug) && !empty($slug))
      {
        $this->PageDataServices->getProductByCategory($slug);
      }
  }

}
