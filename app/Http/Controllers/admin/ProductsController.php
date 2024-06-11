<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Requests\ProductCategoryRequest;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function productCategory()
    {
        $categories = ProductCategory::paginate(10);
        return view('admin.products.product_category.index', compact('categories'));
    }

    public function productCategoryAdd()
    {
        return view('admin.products.product_category.add');
    }

    public function productCategorySave(ProductCategoryRequest $request)
    {
        $slug = Str::slug($request->name);
        ProductCategory::insert(["name"=>$request->name,'slug'=>$slug]);
        return redirect()->route('product-categories-list')->with('success','Product category inserted successfully');
    }

    public function productCategoryUpdate(Request $request)
    {

        $slug = Str::slug($request->name);
        ProductCategory::whereId($request->category_id)->update(["name"=>$request->name,'slug'=>$slug]);
        return redirect()->route('product-categories-list')->with('success','Product category updated successfully');
    }
    public function productCategoryShow($category_id)
    {
        $category = ProductCategory::whereId($category_id)->first();
        return view('admin.products.product_category.edit', compact('category'));
    }

    public function productCategoryDistroy($category_id)
     {
        $category = ProductCategory::whereId($category_id)->delete();
        return redirect()->route('product-categories-list')->with('success','Product category is deleted successfully');
     }

    public function products()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function productAdd()
    {
        return view('admin.products.add');
    }

    public function productSave(ProductCategoryRequest $request)
    {
        $slug = Str::slug($request->name);
        Product::insert(["name"=>$request->name,'slug'=>$slug]);
        return redirect()->route('product-list')->with('success','Product inserted successfully');
    }

    public function productUpdate(Request $request)
    {
        $slug = Str::slug($request->name);
        Product::whereId($request->category_id)->update(["name"=>$request->name,'slug'=>$slug]);
        return redirect()->route('product-list')->with('success','Product updated successfully');
    }




}
