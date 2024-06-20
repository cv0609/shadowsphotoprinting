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
        Schema::create('cart_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('product_id');
            $table->Integer('quantity');
            $table->timestamps();
            $table->foreign('cart_id')->references('id')->on('cart')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_data');
    }
};
