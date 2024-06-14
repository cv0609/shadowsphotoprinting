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
        Schema::create('photo_for_sale_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->string('product_title');
            $table->string('slug');
            $table->decimal('min_price', 10, 2)->default(0)->nullable();
            $table->decimal('max_price', 10, 2)->default(0)->nullable();
            $table->longText('product_description')->nullable()->change();
            $table->string('product_images');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('photo_for_sale_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_for_sale_products');
    }
};
