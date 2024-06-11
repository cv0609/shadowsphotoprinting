<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\ProductRequest;

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
        $products = Product::with(['product_category' => function($query) {
            $query->select('id', 'name'); 
        }])->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function productAdd()
    {
        
        $productCategories = ProductCategory::get();
        return view('admin.products.add',compact('productCategories'));
    }

    public function productSave(ProductRequest $request)
    {

        $slug = Str::slug($request->product_title);

        $data = ["category_id"=>$request->category_id,"product_title"=>preg_replace('/[^\w\s]/',' ', $request->product_title),"product_description"=>$request->product_description,"product_price"=>$request->product_price,"type_of_paper_use"=>$request->type_of_paper_use,'slug'=>$slug];

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('assets/admin/images'), $imageName);
            $product_image = 'assets/admin/images/'.$imageName;
            $data["product_image"] = $product_image;
         }
        Product::insert($data);
        return redirect()->route('product-list')->with('success','Product inserted successfully');
    }

    public function productShow($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $productCategories = ProductCategory::get();
        return view('admin.products.edit', compact('product','productCategories'));
    }

    public function productUpdate(Request $request)
    {
        $slug = Str::slug($request->product_title);

        $data = ["category_id"=>$request->category_id,"product_title"=>preg_replace('/[^\w\s]/',' ', $request->product_title),"product_description"=>$request->product_description,"product_price"=>$request->product_price,"type_of_paper_use"=>$request->type_of_paper_use,'slug'=>$slug];

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('assets/admin/images'), $imageName);
            $product_image = 'assets/admin/images/'.$imageName;
            $data["product_image"] = $product_image;
         }
        Product::whereId($request->product_id)->update($data);
        return redirect()->route('product-list')->with('success','Product updated successfully');
    }


   public function productDistroy($category_id)
    {
       $category = Product::whereId($category_id)->delete();
       return redirect()->route('product-list')->with('success','Product is deleted successfully');
    }

}
