<?php
namespace App\Services;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\GiftCardCategory;
use App\Models\MonthlyEdition;
use App\Models\ShadowsMonthlySetting;
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

    public function getWebBlogs()
    {
       $blogs = Blog::with(['category', 'user'])
           ->where('status','1')
           ->orderByDesc('updated_at')
           ->get();
       if(isset($blogs) && !empty($blogs))
        {
           return $blogs;
        }
        else
        {
            return null;
        } 
    }

    public function getBlogCategories()
    {
        return BlogCategory::orderBy('sort_order')->get();
    }

    public function getLatestMonthlyEdition()
    {
        $withBlogs = ['blogs' => function ($query) {
            $query->where('status', '1')->with(['category', 'user']);
        }];

        $homepage = MonthlyEdition::published()
            ->where('is_homepage', true)
            ->with($withBlogs)
            ->first();

        if ($homepage) {
            return $homepage;
        }

        return MonthlyEdition::published()
            ->with($withBlogs)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('published_at')
            ->first();
    }

    public function getPreviousMonthlyEditions(?MonthlyEdition $latest = null)
    {
        $query = MonthlyEdition::published()
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('published_at');

        if ($latest) {
            $query->where('id', '!=', $latest->id);
        }

        return $query->get();
    }

    public function getLibraryBlogsExcludingEdition(?MonthlyEdition $edition = null)
    {
        $query = Blog::with(['category', 'user'])
            ->where('status', '1')
            ->orderByDesc('updated_at');

        if ($edition) {
            $featuredIds = $edition->blogs->pluck('id')->filter()->all();
            if (!empty($featuredIds)) {
                $query->whereNotIn('id', $featuredIds);
            }
        }

        return $query->get();
    }

    public function getShadowsMonthlySettings(): ShadowsMonthlySetting
    {
        return ShadowsMonthlySetting::current();
    }

    public function getShadowsMonthlyHeroImage(?MonthlyEdition $edition = null): string
    {
        $settings = ShadowsMonthlySetting::current();

        if (!empty($settings->hero_image) && file_exists(public_path($settings->hero_image))) {
            return asset($settings->hero_image);
        }

        $defaultHero = 'assets/images/shadows-monthly/hero.jpg';
        if (file_exists(public_path($defaultHero))) {
            return asset($defaultHero);
        }

        return asset('assets/images/logo.png');
    }

    /**
     * Public URL for a blog image, with space-safe encoding and a fallback when the file is missing.
     */
    public function getBlogImageUrl(?string $path): string
    {
        $fallback = asset('assets/images/logo.png');
        if (empty($path)) {
            return $fallback;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');
        if (!file_exists(public_path($normalized))) {
            return $fallback;
        }

        $encoded = implode('/', array_map('rawurlencode', explode('/', $normalized)));

        return url($encoded);
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