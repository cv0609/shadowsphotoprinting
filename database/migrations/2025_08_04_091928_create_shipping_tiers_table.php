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
        Schema::create('shipping_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // 'scrapbook_page_printing', 'canvas', etc.
            $table->integer('min_quantity');
            $table->integer('max_quantity')->nullable();
            $table->decimal('snail_mail_price', 10, 2);
            $table->decimal('express_price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['category', 'min_quantity', 'max_quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_tiers');
    }
};
