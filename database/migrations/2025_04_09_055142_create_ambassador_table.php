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
        Schema::create('ambassadors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->string('business_name')->nullable();
            $table->string('email')->unique();
            $table->string('website');
            $table->string('social_media_handle');
            $table->string('specialty');
            $table->string('comments')->nullable();
            $table->string('other_specialty')->nullable();
            $table->boolean('is_approved')->default(false);
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
        Schema::dropIfExists('ambassadors');
    }
};
