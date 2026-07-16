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
        Schema::table('cart', function (Blueprint $table) {
            $table->unsignedInteger('shipping_country_id')
                ->default(14)
                ->after('session_id');

            $table->foreign('shipping_country_id')
                ->references('id')
                ->on('countries')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropForeign(['shipping_country_id']);
            $table->dropColumn('shipping_country_id');
        });
    }
};
