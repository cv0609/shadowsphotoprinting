<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('blog_categories')
            ->where('slug', 'inspiration')
            ->update([
                'name' => 'General Inspiration',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('blog_categories')
            ->where('slug', 'inspiration')
            ->update([
                'name' => 'Inspiration',
                'updated_at' => now(),
            ]);
    }
};
