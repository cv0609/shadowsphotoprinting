<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoForSaleCategory;
use App\Models\PhotoForSaleProduct;
use App\Http\Requests\PhotoForSaleCategoryRequest;
use App\Http\Requests\PhotoForSaleProductRequest;
use App\Models\Size;
use App\Models\SizeType;
use App\Models\PhotoForSaleSizePrices;
use App\Services\PageDataService;
use Illuminate\Support\Facades\Session;

class PhotoForSaleController extends Controller
{

    protected $PageDataService;
    public function __construct(PageDataService $PageDataService)
    {
        $this->PageDataService = $PageDataService;
    }

    public function productCategory()
    {
       $categories = PhotoForSaleCategory::get();
       return view('admin.photo_for_sale.category.index',compact('categories'));
    }

    public function productCategoryAdd()
    {
        return view('admin.photo_for_sale.category.add');
    }

    public function productCategorySave(PhotoForSaleCategoryRequest $request)
    {
        $slug = \Str::slug($request->name);
        $image = "";
        if($request->has('image'))
         {
             $file = $request->file('image');
             $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
             $destinationPath = 'assets/admin/uploads/categories';
             $file->move($destinationPath, $fileName);
             $image =  $destinationPath.'/'.$fileName;
         }
        PhotoForSaleCategory::insert(["product_title"=>$request->name,'slug'=>$slug,'product_image'=>$image]);
        return redirect()->route('photos-for-sale-categories-list')->with('success','Category inserted successfully');
    }

    public function productCategoryShow($category_id)
    {
        $category = PhotoForSaleCategory::whereId($category_id)->first();
        return view('admin.photo_for_sale.category.edit', compact('category'));
    }

    public function productCategoryUpdate(Request $request)
    {
        $slug = \Str::slug($request->name);
        $data = ["product_title"=>$request->name,'slug'=>$slug];
        if($request->has('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/admin/uploads/categories';
            $file->move($destinationPath, $fileName);
            $image =  $destinationPath.'/'.$fileName;
            $data['product_image']=$image;
        }
        PhotoForSaleCategory::whereId($request->category_id)->update($data);

        return redirect()->route('photos-for-sale-categories-list')->with('success','Category updated successfully');
    }

    public function productCategoryDistroy($category_id)
    {
       $category = PhotoForSaleCategory::whereId($category_id)->delete();
       return redirect()->route('photos-for-sale-categories-list')->with('success','Category is deleted successfully');
    }


    public function products()
    {
        $products = PhotoForSaleProduct::with(['product_category' => function($query) {
            $query->select('id', 'product_title');
        }])->paginate(10);
        return view('admin.photo_for_sale.index', compact('products'));
    }

    public function productAdd()
    {
        $productCategories = PhotoForSaleCategory::get();
        $size = Size::all();
        $size_type = SizeType::all();

        return view('admin.photo_for_sale.add',compact('productCategories','size','size_type'));
    }

    public function productSave(Request $request)
    {
        $size_arr = $request->size_arr['size'];
        $type_arr = $request->type_arr['type'];
        $price_arr = $request->price_arr['price'];
        $type_size_count = $request->type_size_count['click_count'];



        $slug = \Str::slug($request->product_title);

        $validator = $this->PageDataService->photoForSaleDuplicateSizeTypeValidation($size_arr,$type_arr);
        if(isset($validator)){
            return response()->json(['error' => true,'message' => 'Duplicate entry']);
        }

        $data = ["category_id"=>$request->category_id,"product_title"=>preg_replace('/[^\w\s]/',' ', $request->product_title),"product_description"=>$request->product_description,"min_price"=>$request->min_price,"max_price"=>$request->max_price,'slug'=>$slug,'meta_title' => $request->meta_title,'meta_description' => $request->meta_description];

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $key => $image) {
            $imageName = time().'-'.$key.'.'.$image->getClientOriginalExtension();
            $image->move(public_path('assets/admin/images'), $imageName);
            $product_image = 'assets/admin/images/'.$imageName;
            $product_image_array[] = $product_image;
            }
            $data["product_image"] = implode(',',$product_image_array);
         }

        $productId = PhotoForSaleProduct::insertGetId($data);

        $uniqueCombinations = [];
        foreach ($size_arr as $size_index => $size_data) {
            if (isset($type_arr[$size_index]) && isset($price_arr[$size_index]) && isset($type_size_count[$size_index])) {
                $type_data = $type_arr[$size_index];
                $price_data = $price_arr[$size_index];
                $type_size_count_data = $type_size_count[$size_index];
                foreach ($type_size_count_data['children'] as $count_id) {
                foreach ($price_data['children'] as $price_id) {
                    foreach ($type_data['children'] as $type_id) {
                        foreach ($size_data['children'] as $size_id) {
                            $combinationKey = $size_id . '-' . $type_id . '-' . $price_id . '-'.$count_id;
                            $uniqueCombinations[] = $combinationKey;
                        }
                    }
                }
              }
            }
        }

        foreach ($uniqueCombinations as $combination) {
            list($size_id, $type_id, $price_id,$count_id) = explode('-', $combination);

            PhotoForSaleSizePrices::create([
                'product_id' => $productId,
                'size_id' => $size_id,
                'type_id' => $type_id,
                'price' => $price_id,
                'type_size_count' => $count_id,
            ]);
        }

        Session::flash('success', 'Product inserted successfully');
        return response()->json(['error'=>false,'message' => 'Product inserted successfully.']);
    }

    public function productShow($slug)
    {
        $product = PhotoForSaleProduct::where('slug', $slug)->first();
        $productCategories = PhotoForSaleCategory::get();
        $size = Size::all();
        $size_type = SizeType::all();
        $SaleSizePrices = PhotoForSaleSizePrices::where('product_id',$product->id)->get();
        $SaleSizePricesGroupBy = PhotoForSaleSizePrices::where('product_id',$product->id)->groupBy('type_size_count')->get();
        return view('admin.photo_for_sale.edit', compact('product','productCategories','size','size_type','SaleSizePrices','SaleSizePricesGroupBy'));
    }

    public function productUpdate(Request $request)
    {
        $slug = \Str::slug($request->product_title);

        $size_arr = $request->size_arr['size'] ?? null;
        $type_arr = $request->type_arr['type'] ?? null;
        $price_arr = $request->price_arr['price'] ?? null;
        $type_size_count = $request->type_size_count['click_count'] ?? null;

        if(isset($size_arr,$type_arr,$price_arr,$type_size_count)){
            $validator = $this->PageDataService->photoForSaleDuplicateSizeTypeValidation($size_arr,$type_arr);
            if(isset($validator)){
                return response()->json(['error' => true,'message' => 'Duplicate entry']);
            }
        }

        $data = ["category_id"=>$request->category_id,"product_title"=>preg_replace('/[^\w\s]/',' ', $request->product_title),"product_description"=>$request->product_description,"min_price"=>$request->min_price,"max_price"=>$request->max_price,'slug'=>$slug,'meta_title' => $request->meta_title,'meta_description' => $request->meta_description];

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $key => $image) {
            $imageName = time().'-'.$key.'.'.$image->getClientOriginalExtension();
            $image->move(public_path('assets/admin/images'), $imageName);
            $product_image = 'assets/admin/images/'.$imageName;
            $product_image_array[] = $product_image;
            }
            $data["product_image"] = implode(',',$product_image_array);
         }

        PhotoForSaleProduct::whereId($request->product_id)->update($data);

        PhotoForSaleSizePrices::where('product_id',$request->product_id)->delete();
        if(isset($size_arr) && isset($type_arr) && isset($price_arr) && isset($type_size_count)){


            $uniqueCombinations = [];
            foreach ($size_arr as $size_index => $size_data) {
                if (isset($type_arr[$size_index]) && isset($price_arr[$size_index]) && isset($type_size_count[$size_index])) {
                    $type_data = $type_arr[$size_index];
                    $price_data = $price_arr[$size_index];
                    $type_size_count_data = $type_size_count[$size_index];
                    foreach ($type_size_count_data['children'] as $count_id) {
                    foreach ($price_data['children'] as $price_id) {
                        foreach ($type_data['children'] as $type_id) {
                            foreach ($size_data['children'] as $size_id) {
                                $combinationKey = $size_id . '-' . $type_id . '-' . $price_id . '-'.$count_id;
                                $uniqueCombinations[] = $combinationKey;
                            }
                        }
                    }
                  }
                }
            }

            foreach ($uniqueCombinations as $combination) {
                list($size_id, $type_id, $price_id,$count_id) = explode('-', $combination);

                PhotoForSaleSizePrices::create([
                    'product_id' => $request->product_id,
                    'size_id' => $size_id,
                    'type_id' => $type_id,
                    'price' => $price_id,
                    'type_size_count' => $count_id,
                ]);
            }
        }
        Session::flash('success', 'Product updated successfully.');
        return response()->json(['error'=>false,'message' => 'Product updated successfully.']);
    }


   public function productDistroy($product_id)
    {
       $category = PhotoForSaleProduct::whereId($product_id)->delete();
       return redirect()->route('photos-for-sale-product-list')->with('success','Product is deleted successfully');
    }

}
