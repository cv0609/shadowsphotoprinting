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
        Schema::create('product_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_category_type_id');
            $table->integer('category_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('qty')->default(0);
            $table->enum('status', ['0', '1'])->default(1)->comment('1 for enable and 0 for disable');
            $table->foreign('product_category_type_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock');
    }
};
