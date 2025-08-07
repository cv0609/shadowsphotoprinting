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
            $table->json('shipping_breakdown')->nullable()->after('shipping_charge');
            $table->string('shipping_service')->nullable()->after('shipping_breakdown');
            $table->string('shipping_carrier')->nullable()->after('shipping_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_breakdown', 'shipping_service', 'shipping_carrier']);
        });
    }
};
