<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shadows_monthly_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_image')->nullable();
            $table->unsignedTinyInteger('overlay_opacity')->default(20);
            $table->string('hero_heading')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_cta_text')->nullable();
            $table->string('hero_cta_url')->nullable();
            $table->timestamps();
        });

        $defaultHero = 'assets/images/shadows-monthly/hero.jpg';
        DB::table('shadows_monthly_settings')->insert([
            'hero_image' => file_exists(public_path($defaultHero)) ? $defaultHero : null,
            'overlay_opacity' => 20,
            'hero_heading' => null,
            'hero_subtitle' => null,
            'hero_cta_text' => null,
            'hero_cta_url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('shadows_monthly_settings');
    }
};
