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
            $table->enum('is_test_print', ['0', '1'])->default('0')->after('product_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_data', function (Blueprint $table) {
            $table->dropColumn('is_test_print');
        });
    }
};
