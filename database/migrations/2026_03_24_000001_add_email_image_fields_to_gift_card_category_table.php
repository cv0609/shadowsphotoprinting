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
        Schema::table('gift_card_category', function (Blueprint $table) {
            $table->boolean('use_different_email_image')->default(false)->after('product_image');
            $table->string('email_product_image')->nullable()->after('use_different_email_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gift_card_category', function (Blueprint $table) {
            $table->dropColumn(['use_different_email_image', 'email_product_image']);
        });
    }
};
