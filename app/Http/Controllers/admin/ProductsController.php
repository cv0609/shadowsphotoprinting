<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\ProductRequest;
use App\Models\product_sale;
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
        $image = "";
        if($request->has('image'))
         {
             $file = $request->file('image');
             $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
             $destinationPath = 'assets/admin/uploads/categories';
             $file->move($destinationPath, $fileName);
             $image =  $destinationPath.'/'.$fileName;
         }
        ProductCategory::insert(["name"=>$request->name,'slug'=>$slug,'image'=>$image]);
        return redirect()->route('product-categories-list')->with('success','Product category inserted successfully');
    }

    public function productCategoryUpdate(Request $request)
    {

        $slug = Str::slug($request->name);
        $data = ["name"=>$request->name,'slug'=>$slug];
        if($request->has('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/admin/uploads/categories';
            $file->move($destinationPath, $fileName);
            $image =  $destinationPath.'/'.$fileName;
            $data['image']=$image;
        }
        ProductCategory::whereId($request->category_id)->update($data);

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
        $data = [];
        $slug = Str::slug($request->product_title);

        $data = ["category_id"=>$request->category_id,"product_title"=>preg_replace('/[^\w\s]/',' ', $request->product_title),"product_description"=>$request->product_description,"product_price"=>$request->product_price,"type_of_paper_use"=>$request->type_of_paper_use,'slug'=>$slug];


        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('assets/admin/images'), $imageName);
            $product_image = 'assets/admin/images/'.$imageName;
            $data["product_image"] = $product_image;
         }
         if(isset($request->manage_sale) && $request->manage_sale == "1")
         {
            $data['manage_sale'] = $request->manage_sale;

         }
        $product_id = Product::insertGetId($data);

        if($product_id && isset($request->manage_sale) && $request->manage_sale == "1")
        {
           foreach($request->sale_price as $key =>$value)
            {
              product_sale::insert(["product_id"=>$product_id,"sale_price"=>$value,"sale_start_date"=>$request->sale_start_date[$key],
              "sale_end_date"=>$request->sale_end_date[$key]]);
            }
        }

        return redirect()->route('product-list')->with('success','Product inserted successfully');
    }

    public function productShow($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $productCategories = ProductCategory::get();
        return view('admin.products.edit', compact('product','productCategories'));
    }

    public function productUpdate(ProductRequest $request)
    {
        $data = [];
        $slug = Str::slug($request->product_title);

        $data = ["category_id"=>$request->category_id,"product_title"=>preg_replace('/[^\w\s]/',' ', $request->product_title),"product_description"=>$request->product_description,"product_price"=>$request->product_price,"type_of_paper_use"=>$request->type_of_paper_use,'slug'=>$slug];
        if(isset($request->manage_sale) && $request->manage_sale == "1")
        {

           $data['manage_sale'] = $request->manage_sale;
           $data['sale_price'] = $request->sale_price;
           $data['sale_start_date'] = $request->sale_start_date;
           $data['sale_end_date'] = $request->sale_end_date;
        }
        else
         {
            $data['manage_sale'] = "0";
            $data['sale_price'] = null;
            $data['sale_start_date'] = null;
            $data['sale_end_date'] = null;
         }

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
