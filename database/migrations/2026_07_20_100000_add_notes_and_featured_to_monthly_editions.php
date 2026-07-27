<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monthly_editions', function (Blueprint $table) {
            $table->longText('welcome_note')->nullable()->after('intro');
            $table->longText('editor_note')->nullable()->after('welcome_note');
        });

        Schema::table('monthly_edition_blog', function (Blueprint $table) {
            $table->boolean('is_featured')->default(true)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('monthly_edition_blog', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });

        Schema::table('monthly_editions', function (Blueprint $table) {
            $table->dropColumn(['welcome_note', 'editor_note']);
        });
    }
};
