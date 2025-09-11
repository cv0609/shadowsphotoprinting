<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cart_data', function (Blueprint $table) {
            $table->boolean('is_package')->default(0)->after('watermark_image');
            $table->string('package_price')->nullable()->after('is_package');
            $table->string('package_product_id')->nullable()->after('is_package');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_data', function (Blueprint $table) {
            $table->dropColumn(['is_package', 'package_price', 'package_product_id']);
        });
    }
};
