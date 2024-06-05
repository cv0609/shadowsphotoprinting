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
        Schema::create('page_section_page', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('page_section_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('page_section_id')->references('id')->on('page_sections')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_section_page');
    }
};
