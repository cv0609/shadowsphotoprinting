<?php
namespace App\Services;
use App\Models\Blog;
use App\Models\GiftCardCategory;
use App\Models\ProductCategory;
use App\Models\PhotoForSaleProduct;

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
        $ProductCategories = ProductCategory::get();
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
       $categoryProducts = ProductCategory::with('products')->where('slug',$slug)->first();
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
       return  $data;
     }

    //  public function photoForSaleDuplicateSizeTypeValidation($size_arr,$type_arr){
    //     $uniqueCombinations = [];
    //     foreach ($size_arr as $size_index => $size_data) {
    //         if (isset($type_arr[$size_index])) {
    //             $type_data = $type_arr[$size_index];
    //             foreach ($size_data['children'] as $size_id) {
    //                 foreach ($type_data['children'] as $type_id) {
    //                     $combinationKey = $size_id . '-' . $type_id."<br>";
    //                     if (in_array($combinationKey, $uniqueCombinations)) {
    //                         return true;
    //                     }
    //                     $uniqueCombinations[] = $combinationKey; 
    //                 }
    //             }
    //         }
    //     }
    // }

    public function photoForSaleDuplicateSizeTypeValidation($size_arr, $type_arr, $price_arr) {
        $uniqueCombinations = [];
        foreach ($size_arr as $size_index => $size_data) {
            if (isset($type_arr[$size_index]) && isset($price_arr[$size_index])) {
                $type_data = $type_arr[$size_index];
                $price_data = $price_arr[$size_index];
                foreach ($price_data['children'] as $price_id) {
                    foreach ($type_data['children'] as $type_id) {
                        foreach ($size_data['children'] as $size_id) {
                            $combinationKey = $size_id . '-' . $type_id . '-' . $price_id . "<br>";
                            if (in_array($combinationKey, $uniqueCombinations)) {
                                return true;
                            }
                            $uniqueCombinations[] = $combinationKey;
                        }
                    }
                }
            }
        }

        dd($uniqueCombinations);
        return false;
    }
}