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
        Schema::table('order_billing_details', function (Blueprint $table) {
            $table->string('company_name')->after('state')->nullable();
            $table->string('country_region')->after('company_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_billing_details', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('country_region');
        });
    }
};
