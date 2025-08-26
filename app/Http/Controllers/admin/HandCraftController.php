<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoForSaleCategory;
use App\Models\PhotoForSaleProduct;
use App\Http\Requests\PhotoForSaleCategoryRequest;
use App\Http\Requests\PhotoForSaleProductRequest;
use App\Http\Requests\HandCraftProductRequest;
use App\Http\Requests\HandCraftCategoryRequest;
use App\Http\Requests\HandCraftProductUpdateRequest;
use App\Http\Requests\HandCraftCategoryUpdateRequest;
use App\Models\Size;
use App\Models\SizeType;
use App\Models\ProductStock;
use App\Models\HandCraftCategory;
use App\Models\HandCraftProduct;
use App\Models\PhotoForSaleSizePrices;
use App\Models\ProductCategory;
use App\Services\PageDataService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class HandCraftController extends Controller
{

    protected $PageDataService;
    public function __construct(PageDataService $PageDataService)
    {
        $this->PageDataService = $PageDataService;
    }

    public function productCategory()
    {
       $categories = HandCraftCategory::get();
       return view('admin.hand_craft.category.index',compact('categories'));
    }

    public function productCategoryAdd()
    {
        return view('admin.hand_craft.category.add');
    }

    public function productCategorySave(HandCraftCategoryRequest $request)
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
        HandCraftCategory::insert(["name"=>$request->name,'slug'=>$slug,'image'=>$image]);
        return redirect()->route('hand-craft-categories-list')->with('success','Category inserted successfully');
    }

    public function productCategoryShow($category_id)
    {
        $category = HandCraftCategory::whereId($category_id)->first();
        return view('admin.hand_craft.category.edit', compact('category'));
    }

    public function productCategoryUpdate(HandCraftCategoryUpdateRequest $request)
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
        
        HandCraftCategory::whereId($request->category_id)->update($data);

        return redirect()->route('hand-craft-categories-list')->with('success','Category updated successfully');
    }

    public function productCategoryDistroy($category_id)
    {
       $category = HandCraftCategory::whereId($category_id)->delete();
       return redirect()->route('hand-craft-categories-list')->with('success','Category is deleted successfully');
    }

    public function products()
    {
        $products = HandCraftProduct::with(['product_category' => function($query) {
            $query->select('id', 'name');
        }])->paginate(10);
        return view('admin.hand_craft.index', compact('products'));
    }

    public function productAdd()
    {
        $productCategories = HandCraftCategory::get();
        $hand_craft_main_cat = ProductCategory::where('slug','hand-craft')->first();

        return view('admin.hand_craft.add',compact('productCategories','hand_craft_main_cat'));
    }

    public function productSave(HandCraftProductRequest $request)
    {
        $slug = Str::slug($request->product_title);

        $data = [
            "category_id" => $request->category_id,
            "product_title" => preg_replace('/[^\w\s,.\?]/', ' ', $request->product_title),
            "product_description" => $request->product_description,
            "price" => $request->price,
            'slug' => $slug,
            'sold' => $request->sold ?? 0
        ];

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $key => $image) {
            $imageName = time().'-'.$key.'.'.$image->getClientOriginalExtension();
            $image->move(public_path('assets/admin/images'), $imageName);
            $product_image = 'assets/admin/images/'.$imageName;
            $product_image_array[] = $product_image;
            }
            $data["product_image"] = implode(',',$product_image_array);
         }

        $product_id = HandCraftProduct::insertGetId($data);

        ProductStock::create(['product_category_type_id' => $request->product_category_type_id,'category_id' => $request->category_id , 'product_id' => $product_id , 'qty' => $request->product_qty]);
 


        return redirect()->route('hand-craft-list')->with('success','Product inserted successfully');
    }

    public function productShow($slug)
    {
        $product = HandCraftProduct::where('slug', $slug)->first();
        $productCategories = HandCraftCategory::get();
        $hand_craft_main_cat = ProductCategory::where('slug','hand-craft')->first();

        $product_stock = ProductStock::where(['product_category_type_id' => $hand_craft_main_cat->id , 'category_id' => $product->category_id, 'product_id' => $product->id])->first();

        return view('admin.hand_craft.edit', compact('product','productCategories','product_stock','hand_craft_main_cat'));
    }

    public function productUpdate(HandCraftProductUpdateRequest $request)
    {
        $slug = Str::slug($request->product_title);

        $data = [
            "category_id" => $request->category_id,
            "product_title" => preg_replace('/[^\w\s,.\?]/', ' ', $request->product_title),
            "product_description" => $request->product_description,
            "price" => $request->price,
            'slug' => $slug,
            'sold' => $request->sold ?? 0
        ];

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $key => $image) {
            $imageName = time().'-'.$key.'.'.$image->getClientOriginalExtension();
            $image->move(public_path('assets/admin/images'), $imageName);
            $product_image = 'assets/admin/images/'.$imageName;
            $product_image_array[] = $product_image;
            }
            $data["product_image"] = implode(',',$product_image_array);
        }

        HandCraftProduct::whereId($request->product_id)->update($data);

        ProductStock::updateOrCreate(
            [
                'product_category_type_id' => $request->product_category_type_id,
                'category_id' => $request->category_id,
                'product_id' => $request->product_id
            ],
            [
                'qty' => $request->product_qty,
            ]
        );

        return redirect()->route('hand-craft-list')->with('success', 'Product updated successfully.');
    }


   public function productDistroy($product_id)
    {
       $category = HandCraftProduct::whereId($product_id)->delete();
       return redirect()->route('hand-craft-list')->with('success','Product is deleted successfully');
    }

}
