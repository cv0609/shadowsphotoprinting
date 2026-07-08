<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NzBlockedProductsSeeder extends Seeder
{
    /**
     * Product IDs confirmed by the client (Terri) as not shippable to
     * New Zealand — from "Items not to be shipped to New Zealand.docx".
     *
     * Canvas:      30x60, 40x60, 20x60
     * Posters:     AO, B0
     * Photo Prints: 10x48, 10x48 w/ border, 12x48, 12x48 w/ border
     * Photo Enlargements: 16x48, 20x60, 30x50, 30x60 (both duplicate rows), 36x48, 40x50, 40x60
     *
     * Safe to re-run: sets nz_blocked = 1 only for these IDs, and does not
     * touch any other product's flag.
     */
    private const BLOCKED_PRODUCT_IDS = [
        // Canvas
        9,   // 30x60 Canvas
        18,  // 40x60 Canvas
        20,  // 20x60 Canvas

        // Posters
        149, // AO Poster
        150, // B0 Poster

        // Photo Prints
        52,  // 10x48 Prints
        51,  // 10x48 Prints - With 4mm White Border
        83,  // 12x48 Prints
        82,  // 12x48 Prints - With 4mm White Border

        // Photo Enlargements
        45,  // 16x48
        129, // 20x60
        34,  // 30x50
        30,  // 30x60 (duplicate row 1)
        33,  // 30x60 (duplicate row 2)
        29,  // 36x48
        27,  // 40x50
        26,  // 40x60
    ];

    public function run(): void
    {
        $updated = DB::table('products')
            ->whereIn('id', self::BLOCKED_PRODUCT_IDS)
            ->update([
                'nz_blocked' => 1,
                'updated_at' => now(),
            ]);

        $foundIds = DB::table('products')
            ->whereIn('id', self::BLOCKED_PRODUCT_IDS)
            ->pluck('id')
            ->toArray();

        $missingIds = array_diff(self::BLOCKED_PRODUCT_IDS, $foundIds);

        if (!empty($missingIds)) {
            Log::warning('NzBlockedProductsSeeder: some product IDs were not found in the products table', [
                'missing_ids' => array_values($missingIds),
            ]);
            $this->command?->warn('Warning: these IDs were not found and were skipped: ' . implode(', ', $missingIds));
        }

        $this->command?->info("NZ block flag applied to {$updated} product(s).");
    }
}