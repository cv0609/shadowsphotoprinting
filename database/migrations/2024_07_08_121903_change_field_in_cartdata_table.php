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
            $table->dropColumn('item_type');
            $table->dropColumn('item_desc');
            $table->string('product_type')->nullable()->after('selected_images');
            $table->string('product_desc')->nullable()->after('product_type');
            $table->double('product_price', 12, 2)->nullable()->after('product_desc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_data', function (Blueprint $table) {
            //
        });
    }
};
