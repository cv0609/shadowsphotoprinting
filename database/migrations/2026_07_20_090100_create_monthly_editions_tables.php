<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_editions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->string('cover_image')->nullable();
            $table->string('hero_image')->nullable();
            $table->longText('intro')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('monthly_edition_blog', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monthly_edition_id');
            $table->unsignedInteger('blog_id');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('monthly_edition_id')
                ->references('id')
                ->on('monthly_editions')
                ->cascadeOnDelete();

            $table->foreign('blog_id')
                ->references('id')
                ->on('blogs')
                ->cascadeOnDelete();

            $table->unique(['monthly_edition_id', 'blog_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_edition_blog');
        Schema::dropIfExists('monthly_editions');
    }
};
