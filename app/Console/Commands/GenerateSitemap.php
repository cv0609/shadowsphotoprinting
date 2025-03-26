<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap for the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // ✅ Set your website URL
        $url = 'https://shadowsphotoprinting.com.au';

        // ✅ Generate the sitemap and save it in the public directory
        SitemapGenerator::create($url)
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap generated successfully at public/sitemap.xml!');
    }
}
