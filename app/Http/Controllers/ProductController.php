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

    public function photosForsale()
    {
        return view('photos-for-sale');
    }

    public function giftCard()
    {
        return view('giftcard');
    }
}
