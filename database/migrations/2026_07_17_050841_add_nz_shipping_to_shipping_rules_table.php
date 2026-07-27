<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shipping_rules', function (Blueprint $table) {
            $table->decimal('nz_shipping', 10, 2)
                ->nullable()
                ->after('price');
        });

        $tierPrices = [
            '1-60' => ['snail_mail' => 36.40, 'express' => 47.00],
            '61-100' => ['snail_mail' => 48.85, 'express' => 64.00],
            '101+' => ['snail_mail' => 74.15, 'express' => 96.00],
        ];

        foreach (['photo_prints', 'scrapbook_page_printing'] as $category) {
            foreach ($tierPrices as $condition => $services) {
                foreach ($services as $service => $price) {
                    $this->setNzPrice($category, $condition, $service, $price);
                }
            }
        }

        foreach (['canvas', 'photo_enlargements', 'posters', 'photos_for_sale', 'wedding_package'] as $category) {
            $this->setNzPrice($category, 'fixed', 'snail_mail', 74.15);
            $this->setNzPrice($category, 'fixed', 'express', 96.00);
        }

        $this->setNzPrice('gift_card', 'fixed', 'snail_mail', 0.00);
        $this->setNzPrice('gift_card', 'fixed', 'express', 0.00);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_rules', function (Blueprint $table) {
            $table->dropColumn('nz_shipping');
        });
    }

    private function setNzPrice(string $category, string $condition, string $service, float $price): void
    {
        $categoryId = DB::table('shipping_categories')
            ->where('name', $category)
            ->value('id');

        if (!$categoryId) {
            return;
        }

        DB::table('shipping_rules')
            ->where('shipping_category_id', $categoryId)
            ->where('condition', $condition)
            ->where('service', $service)
            ->update(['nz_shipping' => $price]);
    }
};
