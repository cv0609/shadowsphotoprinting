<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (['photos_for_sale', 'wedding_package'] as $category) {
            $this->setNzPrice($category, 'snail_mail', 74.15);
            $this->setNzPrice($category, 'express', 96.00);
        }

        $this->setNzPrice('gift_card', 'snail_mail', 0.00);
        $this->setNzPrice('gift_card', 'express', 0.00);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The original nz_shipping migration owns these values.
    }

    private function setNzPrice(string $category, string $service, float $price): void
    {
        $categoryId = DB::table('shipping_categories')
            ->where('name', $category)
            ->value('id');

        if (!$categoryId) {
            return;
        }

        DB::table('shipping_rules')
            ->where('shipping_category_id', $categoryId)
            ->where('condition', 'fixed')
            ->where('service', $service)
            ->update(['nz_shipping' => $price]);
    }
};
