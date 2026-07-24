<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('monthly_edition_sections')) {
            return;
        }

        Schema::create('monthly_edition_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monthly_edition_id');
            $table->string('title');
            $table->string('icon')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('image_caption')->nullable();
            $table->string('placement')->default('before_featured');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('monthly_edition_id')
                ->references('id')
                ->on('monthly_editions')
                ->cascadeOnDelete();

            $table->index(['monthly_edition_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_edition_sections');
    }
};
