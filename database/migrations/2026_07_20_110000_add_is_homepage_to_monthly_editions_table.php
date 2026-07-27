<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monthly_editions', function (Blueprint $table) {
            $table->boolean('is_homepage')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('monthly_editions', function (Blueprint $table) {
            $table->dropColumn('is_homepage');
        });
    }
};
