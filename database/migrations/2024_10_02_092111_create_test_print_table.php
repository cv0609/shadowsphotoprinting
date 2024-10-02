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
        Schema::create('test_print', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedInteger('category_id')->nullable(); // Foreign key (category)
            $table->string('product_id')->nullable(); // Product ID as varchar
            $table->float('product_price', 8, 2)->nullable(); // Product price as float with two decimal places
            $table->integer('qty')->nullable(); // Quantity
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_print');
    }
};
