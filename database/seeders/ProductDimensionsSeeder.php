<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductDimensionsSeeder extends Seeder
{
    /**
     * Temporary placeholder dimensions (cm) for products that don't have
     * real length/width/height recorded yet.
     *
     * TODO: Replace these placeholder values once the client provides
     * actual per-product/per-size dimensions, then re-run this seeder
     * (or update the products directly) with real data.
     */
    private const PLACEHOLDER_LENGTH = 10;
    private const PLACEHOLDER_WIDTH  = 12;
    private const PLACEHOLDER_HEIGHT = 10;

    public function run(): void
    {
        // Only fill in products missing dimensions — never overwrite real data
        // that may already exist for some products.
        $updated = DB::table('products')
            ->where(function ($query) {
                $query->whereNull('length')
                    ->orWhereNull('width')
                    ->orWhereNull('height')
                    ->orWhere('length', 0)
                    ->orWhere('width', 0)
                    ->orWhere('height', 0);
            })
            ->update([
                'length' => self::PLACEHOLDER_LENGTH,
                'width'  => self::PLACEHOLDER_WIDTH,
                'height' => self::PLACEHOLDER_HEIGHT,
                'updated_at' => now(),
            ]);

        $length = self::PLACEHOLDER_LENGTH;
        $width  = self::PLACEHOLDER_WIDTH;
        $height = self::PLACEHOLDER_HEIGHT;

        $this->command?->info("Placeholder dimensions ({$length}x{$width}x{$height}cm) applied to {$updated} product(s) missing real dimensions.");
    }
}