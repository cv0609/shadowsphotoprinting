<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function shop()
    {
        return view('shop');
    }

    public function blog()
    {
        return view('blog');
    }

    public function funFacts()
    {
        return view('fun-facts');
    }

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
}
