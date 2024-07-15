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

    //  public function photoForSaleDuplicateSizeTypeValidation(){
    //     $uniqueCombinations = [];

    //     foreach ($size_arr as $size_index => $size_data) {
    //         foreach ($type_arr as $type_index => $type_data) {
    //             foreach ($size_data['children'] as $size_id) {
    //                 foreach ($type_data['children'] as $type_id) {
    //                     foreach ($price_arr[$type_index]['children'] as $price_id) {
    //                         $combinationExists = PhotoForSaleSizePrices::where('product_id', 2)
    //                             ->where('size_id', $size_id)
    //                             ->where('type_id', $type_id)
    //                             ->exists();
    
    //                         if (!$combinationExists) {
    //                             PhotoForSaleSizePrices::create([
    //                                 'product_id' => 2,
    //                                 'size_id' => $size_id,
    //                                 'type_id' => $type_id,
    //                                 'price' => $price_id,
    //                             ]);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //  }
}