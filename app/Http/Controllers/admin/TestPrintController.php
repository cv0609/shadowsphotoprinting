<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\ProductRequest;
use App\Models\product_sale;
use App\Models\TestPrint;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class TestPrintController extends Controller
{
   public function products()
    {
        $products = Product::with(['product_category' => function($query) {
            $query->select('id', 'name');
        }])->paginate(10);
        return view('admin.test_print.index', compact('products'));
    }

    public function productAdd()
    {
        $productCategories = ProductCategory::get();
        return view('admin.test_print.add',compact('productCategories'));
    }

    public function getProudcts(Request $request)
    {
        $category_id = $request->category_id;
        $products = Product::where('category_id', $category_id)->get();

        if ($products->isNotEmpty()) {
            return response()->json(['error' => false, 'data' => $products]);
        } else {
            return response()->json(['error' => true, 'data' => []]);
        }
    }

    public function productSave(Request $request)
    {
        $data = [];
        $productsId =(isset($request->products) && !empty($request->products)) ? implode(',',$request->products) : null;

        $data = ["category_id"=>$request->category_id,"product_id" => $productsId,"product_price"=>$request->product_price,"qty"=>$request->product_qty];

        TestPrint::create($data);

        return redirect()->route('test-print-product-list')->with('success','Product inserted successfully');
    }

    public function productShow($slug)
    {
        $product = Product::with('productSale')->where('slug', $slug)->first();
        $productCategories = ProductCategory::get();
        return view('admin.test_print.edit', compact('product','productCategories'));
    }

    public function productUpdate(ProductRequest $request)
    {
        // dd($request->manage_sale);
        $data = [];
        $slug = Str::slug($request->product_title);

        $data = ["category_id"=>$request->category_id,"product_title"=>preg_replace('/[^\w\s\(\)\.]/', ' ', $request->product_title)
        ,"product_description"=>$request->product_description,"product_price"=>$request->product_price,"type_of_paper_use"=>$request->type_of_paper_use,'slug'=>$slug];

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('assets/admin/images'), $imageName);
            $product_image = 'assets/admin/images/'.$imageName;
            $data["product_image"] = $product_image;
        }

        if(isset($request->manage_sale) && $request->manage_sale == "1"){
            $data['manage_sale'] = '1';
        }else{
            $data['manage_sale'] = '0';
        }

        Product::whereId($request->product_id)->update($data);

        product_sale::where('product_id',$request->product_id)->delete();

        if($request->product_id && isset($request->manage_sale) && $request->manage_sale == "1")
        {
           foreach($request->sale_price as $key =>$value)
            {
              product_sale::insert(["product_id"=>$request->product_id,"sale_price"=>$value,"sale_start_date"=>$request->sale_start_date[$key],
              "sale_end_date"=>$request->sale_end_date[$key]]);
            }
        }
        return redirect()->route('test-print-product-list')->with('success','Product updated successfully');
    }


   public function productDistroy($category_id)
    {
       $category = Product::whereId($category_id)->delete();
       return redirect()->route('test-print-product-list')->with('success','Product is deleted successfully');
    }
}
