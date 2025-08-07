<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingTier;

class ShippingTiersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing tiers
        ShippingTier::where('category', 'scrapbook_page_printing')->delete();

        // Scrapbook Page Printing Tiers
        $tiers = [
            [
                'category' => 'scrapbook_page_printing',
                'min_quantity' => 1,
                'max_quantity' => 60,
                'snail_mail_price' => 15.00,
                'express_price' => 20.00,
                'is_active' => true
            ],
            [
                'category' => 'scrapbook_page_printing',
                'min_quantity' => 61,
                'max_quantity' => 100,
                'snail_mail_price' => 18.40,
                'express_price' => 22.65,
                'is_active' => true
            ],
            [
                'category' => 'scrapbook_page_printing',
                'min_quantity' => 101,
                'max_quantity' => null, // No upper limit
                'snail_mail_price' => 22.15,
                'express_price' => 30.21,
                'is_active' => true
            ]
        ];

        foreach ($tiers as $tier) {
            ShippingTier::create($tier);
        }

        $this->command->info('Shipping tiers seeded successfully!');
    }
}
