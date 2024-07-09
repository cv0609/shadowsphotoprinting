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
        Schema::create('photo_for_sale_size_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->integer('size_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_for_sale_size_prices');
    }
};
