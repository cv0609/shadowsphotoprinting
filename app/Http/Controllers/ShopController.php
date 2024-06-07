<?php

namespace App\Http\Controllers;
use App\Models\Page;

use Illuminate\Http\Request;

class ShopController extends Controller
{

    
    public function shop()
    {
        $content = Page::where('slug','shop')->with('pageSections')->first();
     
        if($content && isset($content->pageSections) && !empty($content->pageSections))
         {
           $page_content = json_decode($content->pageSections['content'],true);
           return view('shop',compact('page_content'));
         }
         else
         {
            abort(404);
         }
    }
}
