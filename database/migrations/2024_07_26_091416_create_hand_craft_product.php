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
        Schema::create('hand_craft_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->string('product_title')->nullable();
            $table->string('slug')->nullable();
            $table->decimal('price', 10, 2)->default(0)->nullable();
            $table->longText('product_description')->nullable();
            $table->string('product_image');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('hand_craft_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hand_craft_product');
    }
};
