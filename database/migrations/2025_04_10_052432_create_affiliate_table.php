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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('referral_code');
            $table->string('coupon_code');
            $table->integer('referral_count')->default(0);
            $table->integer('referral_sales_count')->default(0);
            $table->float('commission')->default(0);
            $table->unsignedBigInteger('user_id')->nullable(); /// Associate with users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
