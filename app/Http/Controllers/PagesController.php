<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PagesController extends Controller
{
    public function home()
    {

        $content = Page::where('slug','home')->with('pageSections')->first();
     
        if($content && isset($content->pageSections) && !empty($content->pageSections))
         {
           $page_content = json_decode($content->pageSections['content'],true);
           return view('home',compact('page_content'));
         }
         else
         {
            abort(404);
         }
    }

    public function funFacts()
    {
        return view('fun-facts');
    }
}
