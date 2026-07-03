<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NzShippingSeeder extends Seeder
{
    /**
     * Seed the NZ AusPost Zone 1 shipping category and its weight-based rate tiers.
     * Safe to re-run: updates the category if it exists, and replaces its rules
     * each time rather than duplicating them.
     */
    public function run(): void
    {
        // 1. Create or update the shipping category
        DB::table('shipping_categories')->updateOrInsert(
            ['name' => 'nz_auspost_zone1'],
            [
                'display_name' => 'NZ AusPost Zone 1 - Standard',
                'pricing_type' => 'tier',
                'carriers'     => json_encode(['auspost']),
                'is_active'    => 1,
                'updated_at'   => now(),
                'created_at'   => now(),
            ]
        );

        $categoryId = DB::table('shipping_categories')
            ->where('name', 'nz_auspost_zone1')
            ->value('id');

        // 2. Clear any existing rules for this category before re-inserting,
        //    so re-running this seeder never creates duplicate rate rows.
        DB::table('shipping_rules')->where('shipping_category_id', $categoryId)->delete();

        // 3. Confirmed NZ AusPost Zone 1 Standard rates (weight in kg)
        $rules = [
            ['condition' => '0-0.25',    'price' => 17.90],
            ['condition' => '0.26-0.5',  'price' => 21.60],
            ['condition' => '0.51-1',    'price' => 29.00],
            ['condition' => '1.01-1.5',  'price' => 36.40],
            ['condition' => '1.51-2',    'price' => 43.80],
            ['condition' => '2.01-2.5',  'price' => 48.85],
            ['condition' => '2.51-3',    'price' => 53.90],
            ['condition' => '3.01-5',    'price' => 74.15],
            ['condition' => '5.01-10',   'price' => 124.85],
            ['condition' => '10.01-15',  'price' => 175.45],
            ['condition' => '15.01-20',  'price' => 226.10],
        ];

        foreach ($rules as $priority => $rule) {
            DB::table('shipping_rules')->insert([
                'shipping_category_id' => $categoryId,
                'rule_type'            => 'weight_based',
                'condition'            => $rule['condition'],
                'carrier'              => 'auspost',
                'service'              => 'standard',
                'price'                => $rule['price'],
                'delivery_time'        => '5-10 business days',
                'is_active'            => 1,
                'priority'             => $priority + 1,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }

        $this->command?->info('NZ AusPost Zone 1 shipping category and ' . count($rules) . ' rate tiers seeded.');
    }
}