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
        Schema::table('orders', function (Blueprint $table) {
            // Remove order_id column
            $table->dropColumn('order_id');

            // Add new columns
            $table->unsignedBigInteger('coupon_id')->nullable()->after('order_number');
            $table->decimal('discount',10, 2)->nullable()->after('coupon_id');

            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('user_session_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add the order_id column back
            $table->string('order_id');

            // Remove the added columns
            $table->dropColumn('coupon_id');
            $table->dropColumn('discount');
        });
    }
};
