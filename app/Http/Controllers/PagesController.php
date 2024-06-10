<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PagesController extends Controller
{
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

    // public function quote()
    // {
    //     $content = Page::where('slug','quote')->with('pageSections')->first();

    //     if($content && isset($content->pageSections) && !empty($content->pageSections))
    //      {
    //        $page_content = json_decode($content->pageSections['content'],true);
    //        return view('get-a-quote',compact('page_content'));
    //      }
    //      else
    //      {
    //         abort(404);
    //      }
    // }

    // public function funFacts()
    // {
    //     return view('fun-facts');
    // }


}
