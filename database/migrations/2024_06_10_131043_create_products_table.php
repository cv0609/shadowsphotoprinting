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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->string('product_title');
            $table->string('slug');
            $table->decimal('product_price', 10, 2)->default(0)->nullable();
            $table->longText('product_description');
            $table->string('type_of_paper_use');
            $table->string('product_image')->nullable();
            $table->enum('manage_sale',['0','1'])->comment('0 for not sale, 1 for sale')->default("0");
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
