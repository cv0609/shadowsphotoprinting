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
        Schema::table('shipping', function (Blueprint $table) {
            $table->enum('is_test_print',['0','1'])->default('0')->comment('0 means not test print , 1 means is test print.')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping', function (Blueprint $table) {
            $table->dropColumn('is_test_print'); // Remove the column on rollback
        });
    }
};
