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
            $table->string('item_type')->nullable()->after('selected_images');
            $table->string('item_desc')->nullable()->after('item_type');
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
