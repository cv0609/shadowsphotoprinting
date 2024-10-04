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
            $table->float('test_print_price')->nullable()->after('is_test_print');
            $table->integer('test_print_qty')->nullable()->after('test_print_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_data', function (Blueprint $table) {
            $table->dropColumn(['test_print_price', 'test_print_qty']);
        });
    }
};
