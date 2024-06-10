<?php

namespace App\Http\Controllers;
use App\Models\Page;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function ourProducts()
    {
        return view('our-products');
    }

    public function scrapbookPrints()
    {

        $content = Page::where('slug','scrapbook')->with('pageSections')->first();
     
        if($content && isset($content->pageSections) && !empty($content->pageSections))
        {
            $page_content = json_decode($content->pageSections['content'],true);
            return view('scrapbook-prints',compact('page_content'));
        }
        else
        {
            abort(404);
        }
    }
    public function canvasPrints()
    {

        $content = Page::where('slug','canvas')->with('pageSections')->first();
     
        if($content && isset($content->pageSections) && !empty($content->pageSections))
         {
           $page_content = json_decode($content->pageSections['content'],true);
           return view('canvas-prints',compact('page_content'));
         }
         else
         {
            abort(404);
         }
    }

    public function postersPanoramics()
    {
        return view('posters-panoramics');
    }

    public function printEnlargements()
    {
        return view('prints-enlargements');
    }

    public function photos()
    {
        return view('photos');
    }

    public function giftCard()
    {
        return view('giftcard');
    }

    public function centralWest()
    {
        return view('central-west-n-s-w');
    }

    public function childrensPhotos()
    {
        return view('childrens-photos');
    }

    public function countrySidevictoria()
    {
        return view('countryside-victoria');
    }

    public function outback()
    {
        return view('outback-n-s-w');
    }

    public function poems()
    {
        return view('poems');
    }
}
