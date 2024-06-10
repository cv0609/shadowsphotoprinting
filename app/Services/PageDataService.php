<?php
namespace App\Services;
use App\Models\Blog;
class PageDataService
{
   public function getBlogs()
    {
       $blogs = Blog::get();
       if(isset($blogs) && !empty($blogs))
         {
            return $blogs;
         }
        else{
            return null;
        } 
    }
}