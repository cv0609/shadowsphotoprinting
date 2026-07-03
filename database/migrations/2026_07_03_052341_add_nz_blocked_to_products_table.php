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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('nz_blocked')->default(false)->after('category_id')
                ->comment('If true, this product/size cannot be sold or shipped to New Zealand customers');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('nz_blocked');
        });
    }
};
