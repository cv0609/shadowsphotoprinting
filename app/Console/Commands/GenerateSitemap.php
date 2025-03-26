<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;
use App\Models\Product; // Ensure this model exists and is correctly set up

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap for shadowsphotoprinting.com.au';

    public function handle()
    {
        $baseUrl = 'https://shadowsphotoprinting.com.au';

        $sitemap = Sitemap::create()
            // Add static pages
            ->add(Url::create("$baseUrl/")->setPriority(1.0))
            ->add(Url::create("$baseUrl/shop")->setPriority(0.9))
            ->add(Url::create("$baseUrl/blog")->setPriority(0.8))
            ->add(Url::create("$baseUrl/promotions")->setPriority(0.8))
            ->add(Url::create("$baseUrl/fun-facts")->setPriority(0.7))
            ->add(Url::create("$baseUrl/get-a-quote")->setPriority(0.6))
            ->add(Url::create("$baseUrl/contact-us")->setPriority(0.6))
            // Add product categories
            ->add(Url::create("$baseUrl/product-category/scrapbook-page-printing")->setPriority(0.9))
            ->add(Url::create("$baseUrl/product-category/canvas-prints")->setPriority(0.9))
            ->add(Url::create("$baseUrl/product-category/photo-enlargements")->setPriority(0.9))
            ->add(Url::create("$baseUrl/product-category/photo-prints")->setPriority(0.9))
            ->add(Url::create("$baseUrl/product-category/photos-for-sale")->setPriority(0.9))
            ->add(Url::create("$baseUrl/product-category/gift-card")->setPriority(0.9))
            ->add(Url::create("$baseUrl/product-category/hand-craft")->setPriority(0.9))
            ->add(Url::create("$baseUrl/product-category/poster-prints")->setPriority(0.9));

        // Add dynamic product pages
        $products = Product::all();
        foreach ($products as $product) {
            $sitemap->add(
                Url::create("$baseUrl/product/{$product->slug}")
                    ->setLastModificationDate($product->updated_at ?? Carbon::now())
                    ->setPriority(0.8)
            );
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap generated successfully!');
    }
}
