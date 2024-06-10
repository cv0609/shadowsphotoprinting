<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductsController extends Controller
{
    public function productCategory()
    {
        $categories = ProductCategory::paginate(10);
        return view('admin.products.products_category.index', compact('categories'));
    }
}
