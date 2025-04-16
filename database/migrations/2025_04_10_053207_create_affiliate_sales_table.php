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
        Schema::create('affiliate_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->float('commission');
            $table->float('shutter_points');
            $table->string('operation');
            $table->timestamps();
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_sales');
    }
};
