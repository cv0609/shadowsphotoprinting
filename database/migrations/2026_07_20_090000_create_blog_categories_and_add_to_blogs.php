<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $categories = [
            'Photography Tips',
            'Printing Advice',
            'Product Guides',
            'Family Stories',
            'Community Stories',
            'Behind the Scenes',
            'Helpful Information',
            'Inspiration',
        ];

        $now = now();
        foreach ($categories as $index => $name) {
            DB::table('blog_categories')->insert([
                'name' => $name,
                'slug' => Str::slug($name),
                'sort_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Schema::table('blogs', function (Blueprint $table) {
            $table->unsignedBigInteger('blog_category_id')->nullable()->after('user_id');
            $table->foreign('blog_category_id')
                ->references('id')
                ->on('blog_categories')
                ->nullOnDelete();
        });

        $defaultCategoryId = DB::table('blog_categories')
            ->where('slug', 'helpful-information')
            ->value('id');

        if ($defaultCategoryId) {
            DB::table('blogs')->whereNull('blog_category_id')->update([
                'blog_category_id' => $defaultCategoryId,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['blog_category_id']);
            $table->dropColumn('blog_category_id');
        });

        Schema::dropIfExists('blog_categories');
    }
};
