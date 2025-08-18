<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingCategory;
use App\Models\ShippingRule;
use App\Models\ProductShippingMapping;

class ShippingDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        ShippingRule::truncate();
        ProductShippingMapping::truncate();
        ShippingCategory::truncate();

        // 1. Scrapbook Page Printing (Tier-based for separate orders)
        $scrapbookCategory = ShippingCategory::create([
            'name' => 'scrapbook_page_printing',
            'display_name' => 'Scrapbook Page Printing',
            'pricing_type' => 'tier',
            'carriers' => ['auspost'],
            'is_active' => true
        ]);

        // Scrapbook rules (tier-based) - Updated pricing
        $scrapbookRules = [
            ['condition' => '1-60', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 15.00, 'delivery_time' => '5-10 business days', 'priority' => 1],
            ['condition' => '1-60', 'carrier' => 'auspost', 'service' => 'express', 'price' => 20.00, 'delivery_time' => '1-2 business days', 'priority' => 2],
            ['condition' => '61-100', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 18.40, 'delivery_time' => '5-10 business days', 'priority' => 3],
            ['condition' => '61-100', 'carrier' => 'auspost', 'service' => 'express', 'price' => 22.65, 'delivery_time' => '1-2 business days', 'priority' => 4],
            ['condition' => '101+', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 22.60, 'delivery_time' => '5-10 business days', 'priority' => 5],
            ['condition' => '101+', 'carrier' => 'auspost', 'service' => 'express', 'price' => 30.21, 'delivery_time' => '1-2 business days', 'priority' => 6],
        ];

        foreach ($scrapbookRules as $rule) {
            ShippingRule::create(array_merge($rule, [
                'shipping_category_id' => $scrapbookCategory->id,
                'rule_type' => 'quantity_based'
            ]));
        }

        // 2. Photo Prints (Tier-based for separate orders) - Updated pricing
        $photoPrintsCategory = ShippingCategory::create([
            'name' => 'photo_prints',
            'display_name' => 'Photo Prints',
            'pricing_type' => 'tier',
            'carriers' => ['auspost'],
            'is_active' => true
        ]);

        // Photo Prints rules (tier-based) - Updated pricing
        $photoPrintsRules = [
            ['condition' => '1-60', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 15.00, 'delivery_time' => '5-10 business days', 'priority' => 1],
            ['condition' => '1-60', 'carrier' => 'auspost', 'service' => 'express', 'price' => 20.00, 'delivery_time' => '1-2 business days', 'priority' => 2],
            ['condition' => '61-100', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 18.40, 'delivery_time' => '5-10 business days', 'priority' => 3],
            ['condition' => '61-100', 'carrier' => 'auspost', 'service' => 'express', 'price' => 22.65, 'delivery_time' => '1-2 business days', 'priority' => 4],
            ['condition' => '101+', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 22.60, 'delivery_time' => '5-10 business days', 'priority' => 5],
            ['condition' => '101+', 'carrier' => 'auspost', 'service' => 'express', 'price' => 30.21, 'delivery_time' => '1-2 business days', 'priority' => 6],
        ];

        foreach ($photoPrintsRules as $rule) {
            ShippingRule::create(array_merge($rule, [
                'shipping_category_id' => $photoPrintsCategory->id,
                'rule_type' => 'quantity_based'
            ]));
        }

        // 3. Canvas (Fixed pricing - Australia Post only)
        $canvasCategory = ShippingCategory::create([
            'name' => 'canvas',
            'display_name' => 'Canvas',
            'pricing_type' => 'fixed',
            'carriers' => ['auspost'],
            'is_active' => true
        ]);

        // Canvas rules (fixed pricing - Australia Post only)
        $canvasRules = [
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 22.60, 'delivery_time' => '5-10 business days', 'priority' => 1],
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'express', 'price' => 31.21, 'delivery_time' => '1-2 business days', 'priority' => 2],
        ];

        foreach ($canvasRules as $rule) {
            ShippingRule::create(array_merge($rule, [
                'shipping_category_id' => $canvasCategory->id,
                'rule_type' => 'fixed'
            ]));
        }

        // 4. Photo Enlargements (Fixed pricing)
        $photoEnlargementCategory = ShippingCategory::create([
            'name' => 'photo_enlargements',
            'display_name' => 'Photo Enlargements',
            'pricing_type' => 'fixed',
            'carriers' => ['auspost'],
            'is_active' => true
        ]);

        // Photo Enlargement rules (fixed pricing)
        $photoEnlargementRules = [
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 22.60, 'delivery_time' => '5-10 business days', 'priority' => 1],
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'express', 'price' => 31.21, 'delivery_time' => '1-2 business days', 'priority' => 2],
        ];

        foreach ($photoEnlargementRules as $rule) {
            ShippingRule::create(array_merge($rule, [
                'shipping_category_id' => $photoEnlargementCategory->id,
                'rule_type' => 'fixed'
            ]));
        }

        // 5. Posters (Fixed pricing)
        $postersCategory = ShippingCategory::create([
            'name' => 'posters',
            'display_name' => 'Posters',
            'pricing_type' => 'fixed',
            'carriers' => ['auspost'],
            'is_active' => true
        ]);

        // Posters rules (fixed pricing)
        $postersRules = [
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 22.60, 'delivery_time' => '5-10 business days', 'priority' => 1],
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'express', 'price' => 31.21, 'delivery_time' => '1-2 business days', 'priority' => 2],
        ];

        foreach ($postersRules as $rule) {
            ShippingRule::create(array_merge($rule, [
                'shipping_category_id' => $postersCategory->id,
                'rule_type' => 'fixed'
            ]));
        }

        // 6. Hand Craft (Fixed pricing)
        $handCraftCategory = ShippingCategory::create([
            'name' => 'hand_craft',
            'display_name' => 'Hand Craft',
            'pricing_type' => 'fixed',
            'carriers' => ['auspost'],
            'is_active' => true
        ]);

        // Hand Craft rules (fixed pricing)
        $handCraftRules = [
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 22.60, 'delivery_time' => '5-10 business days', 'priority' => 1],
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'express', 'price' => 31.21, 'delivery_time' => '1-2 business days', 'priority' => 2],
        ];

        foreach ($handCraftRules as $rule) {
            ShippingRule::create(array_merge($rule, [
                'shipping_category_id' => $handCraftCategory->id,
                'rule_type' => 'fixed'
            ]));
        }

        // 7. Photos for Sale (Fixed pricing)
        $photosForSaleCategory = ShippingCategory::create([
            'name' => 'photos_for_sale',
            'display_name' => 'Photos for Sale',
            'pricing_type' => 'fixed',
            'carriers' => ['auspost'],
            'is_active' => true
        ]);

        // Photos for Sale rules (fixed pricing)
        $photosForSaleRules = [
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 22.60, 'delivery_time' => '5-10 business days', 'priority' => 1],
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'express', 'price' => 31.21, 'delivery_time' => '1-2 business days', 'priority' => 2],
        ];

        foreach ($photosForSaleRules as $rule) {
            ShippingRule::create(array_merge($rule, [
                'shipping_category_id' => $photosForSaleCategory->id,
                'rule_type' => 'fixed'
            ]));
        }

        // 8. Gift Card (Zero shipping)
        $giftCardCategory = ShippingCategory::create([
            'name' => 'gift_card',
            'display_name' => 'Gift Card',
            'pricing_type' => 'fixed',
            'carriers' => ['auspost'],
            'is_active' => true
        ]);

        // Gift Card rules (zero shipping)
        $giftCardRules = [
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'snail_mail', 'price' => 0.00, 'delivery_time' => '5-10 business days', 'priority' => 1],
            ['condition' => 'fixed', 'carrier' => 'auspost', 'service' => 'express', 'price' => 0.00, 'delivery_time' => '1-2 business days', 'priority' => 2],
        ];

        foreach ($giftCardRules as $rule) {
            ShippingRule::create(array_merge($rule, [
                'shipping_category_id' => $giftCardCategory->id,
                'rule_type' => 'fixed'
            ]));
        }

        // Product Category Mappings
        $mappings = [
            ['product_category_id' => 1, 'shipping_category_id' => $scrapbookCategory->id], // Scrapbook
            ['product_category_id' => 2, 'shipping_category_id' => $canvasCategory->id], // Canvas
            ['product_category_id' => 3, 'shipping_category_id' => $photoEnlargementCategory->id], // Photo Enlargements
            ['product_category_id' => 4, 'shipping_category_id' => $photoPrintsCategory->id], // Photo Prints
            ['product_category_id' => 5, 'shipping_category_id' => $postersCategory->id], // Posters
            ['product_category_id' => 6, 'shipping_category_id' => $handCraftCategory->id], // Hand Craft
            ['product_category_id' => 7, 'shipping_category_id' => $photosForSaleCategory->id], // Photos for Sale
            ['product_category_id' => 8, 'shipping_category_id' => $giftCardCategory->id], // Gift Card
        ];

        foreach ($mappings as $mapping) {
            ProductShippingMapping::create($mapping);
        }

        $this->command->info('Shipping data seeded successfully!');
        $this->command->info('Updated pricing structure:');
        $this->command->info('- Combined orders: Fixed pricing ($22.60 snail mail, $31.21 express)');
        $this->command->info('- Separate Photo Print/Scrapbook orders: Tier-based pricing based on combined quantity');
        $this->command->info('  * 1-60 prints: $15.00 snail mail, $20.00 express');
        $this->command->info('  * 61-100 prints: $18.40 snail mail, $22.65 express');
        $this->command->info('  * 101+ prints: $22.60 snail mail, $30.21 express');
        $this->command->info('Created shipping categories: Scrapbook Page Printing, Photo Prints, Canvas, Photo Enlargements, Posters, Hand Craft, Photos for Sale, Gift Card');
    }
} 