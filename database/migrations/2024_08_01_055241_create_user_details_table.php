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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('street1')->nullable();
            $table->string('street2')->nullable();
            $table->string('state')->nullable();
            $table->unsignedBigInteger('postcode')->nullable();
            $table->unsignedBigInteger('phone')->nullable();
            $table->string('suburb')->nullable();
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->string('country_region')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->enum('isShippingAddress', ['0', '1'])->default('0');
            $table->string('ship_fname')->nullable();
            $table->string('ship_lname')->nullable();
            $table->string('ship_company')->nullable();
            $table->string('ship_country_region')->nullable();
            $table->string('ship_street1')->nullable();
            $table->string('ship_street2')->nullable();
            $table->string('ship_suburb')->nullable();
            $table->string('ship_state')->nullable();
            $table->unsignedBigInteger('ship_postcode')->nullable();
            $table->text('order_comments')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
