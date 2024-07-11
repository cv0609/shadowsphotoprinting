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
        Schema::table('order_details', function (Blueprint $table) {
            $table->string('product_type')->after('price')->nullable();
            $table->text('product_desc')->after('product_type')->nullable();
            $table->decimal('product_price', 10, 2)->after('product_desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('product_type');
            $table->dropColumn('product_desc');
            $table->dropColumn('product_price');
        });
    }
};
