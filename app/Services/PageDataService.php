<?php
namespace App\Services;
use App\Models\Blog;
use App\Models\GiftCardCategory;
use App\Models\ProductCategory;
use App\Models\PhotoForSaleProduct;
use App\Models\Admin;
use App\Models\HandCraftProduct;
use App\Models\Product;

class PageDataService
{
    public function getBlogs()
    {
       $blogs = Blog::get();
       if(isset($blogs) && !empty($blogs))
        {
           return $blogs;
        }
        else
        {
            return null;
        } 
    }

    public function getProductCategories()
    {
        $ProductCategories = ProductCategory::where('slug' ,'!=','test-print')->get();
        if(isset($ProductCategories) && !empty($ProductCategories))
        {
            return $ProductCategories;
        }
        else
        {
            return null;
        }    
    }

    public function getProductCategoriesForBulk()
    {
        $ProductCategories = ProductCategory::where('slug' ,'!=','test-print')->where('slug' ,'!=','gift-card')->where('slug' ,'!=','hand-craft')->get();
        if(isset($ProductCategories) && !empty($ProductCategories))
        {
            return $ProductCategories;
        }
        else
        {
            return null;
        }    
    }

    public function getProductBySlug($slug)
     {
        $categoryProducts = ProductCategory::with(['products' => function ($query) {
            $query->orderBy('position', 'asc');
        }])->where('slug', $slug)->first();
        
       if(isset($categoryProducts) && !empty($categoryProducts))
       {
           return $categoryProducts->products;
       }
       else
       {
           return null;
       }  
     }

     public function getShopProductsBySlug()
     {
       $data['giftcardCount'] = GiftCardCategory::count();
       $data['photoSaleCount'] = PhotoForSaleProduct::count();
       $data['productCount'] = Product::count();
       $data['handCraftCount'] = HandCraftProduct::count();
       return  $data;
     }

       public function photoForSaleDuplicateSizeTypeValidation($size_arr,$type_arr){
        $uniqueCombinations = [];
        foreach ($size_arr as $size_index => $size_data) {
            if (isset($type_arr[$size_index])) {
                $type_data = $type_arr[$size_index];
                foreach ($size_data['children'] as $size_id) {
                    foreach ($type_data['children'] as $type_id) {
                        $combinationKey = $size_id . '-' . $type_id;
                        if (in_array($combinationKey, $uniqueCombinations)) {
                            return true;
                        }
                        $uniqueCombinations[] = $combinationKey; 
                    }
                }
            }
        }
        // dd($uniqueCombinations);
    }

    public function dashboard_index(){
        $admin = Admin::all();
        if(isset($admin) && !empty($admin)){
            return $admin[0]->set_index;
        }
        return '0';
    }

    public function getNewzLetter()
    {
       $blogs = Blog::get();
       if(isset($blogs) && !empty($blogs))
        {
           return $blogs;
        }
        else
        {
            return null;
        } 
    }
}