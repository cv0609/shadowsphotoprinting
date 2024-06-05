<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function ourProducts()
    {
        return view('our-products');
    }

    public function scrapbookPrints()
    {
        return view('scrapbook-prints');
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
