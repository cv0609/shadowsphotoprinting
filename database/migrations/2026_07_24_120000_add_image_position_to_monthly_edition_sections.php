<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monthly_edition_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('monthly_edition_sections', 'image_position')) {
                $table->string('image_position')->default('full_width')->after('image_caption');
            }
        });
    }

    public function down(): void
    {
        Schema::table('monthly_edition_sections', function (Blueprint $table) {
            if (Schema::hasColumn('monthly_edition_sections', 'image_position')) {
                $table->dropColumn('image_position');
            }
        });
    }
};
