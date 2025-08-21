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
        Schema::create('shipping_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_category_id')->constrained()->onDelete('cascade');
            $table->string('rule_type'); // size_based, quantity_based, fixed
            $table->string('condition'); // product size, quantity range, etc.
            $table->string('carrier'); // auspost, aramex
            $table->string('service'); // snail_mail, express
            $table->decimal('price', 10, 2)->nullable(); // null for API calculated
            $table->string('delivery_time');
            $table->json('dimensions')->nullable(); // box dimensions for API
            $table->decimal('weight', 8, 2)->nullable(); // weight in kg
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // for rule ordering
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rules');
    }
}; 