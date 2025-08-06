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
        Schema::create('shipping_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // scrapbook_page_printing, canvas, photo_enlargements
            $table->string('display_name'); // Scrapbook Page Printing, Canvas, Photo Enlargements
            $table->enum('pricing_type', ['tier', 'fixed', 'api']); // tier-based, fixed pricing, API calculated
            $table->json('carriers')->nullable(); // ['auspost', 'aramex'] or just ['auspost']
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_categories');
    }
}; 