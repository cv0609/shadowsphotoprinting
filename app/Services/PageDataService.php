<?php
namespace App\Services;
use App\Models\Blog;
use App\Models\ProductCategory;
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

    public function getProductByCategory($category_id)
     {
        ProductCategory::with('products')->where('category_id ',$category_id)->get();
     }
}