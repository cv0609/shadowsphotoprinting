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
        // $products = Product::with(['product_category' => function($query) {
        //     $query->select('id', 'name');
        // }])->paginate(10);
        $products = TestPrint::with('product_category')->paginate(10);
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

    public function productShow($cat_id)
    {
        $testPrint = TestPrint::where('id', $cat_id)->first();
        $cat_id = $testPrint->category_id;
        $product_arr = explode(',',$testPrint->product_id);

        $productCategories = ProductCategory::get();

        $products = Product::where('category_id', $cat_id)->get();
        $test_print_product = Product::whereIn('id', $product_arr)->get();
        $products_ids = $test_print_product->pluck('id')->toArray();

        return view('admin.test_print.edit', compact('testPrint','productCategories','cat_id','products_ids','test_print_product','products'));
    }

    public function productUpdate(Request $request)
    {
        $data = [];
        $productsId =(isset($request->products) && !empty($request->products)) ? implode(',',$request->products) : null;

        $data = ["category_id"=>$request->category_id,"product_id" => $productsId,"product_price"=>$request->product_price,"qty"=>$request->product_qty];

        TestPrint::where('id',$request->test_print_id)->update($data);

        return redirect()->route('test-print-product-list')->with('success','Product updated successfully');
    }


   public function productDistroy($category_id)
    {
       $category = TestPrint::whereId($category_id)->delete();
       return redirect()->route('test-print-product-list')->with('success','Product is deleted successfully');
    }
}
