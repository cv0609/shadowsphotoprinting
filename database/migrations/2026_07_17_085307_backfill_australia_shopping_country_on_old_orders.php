<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $australiaId = DB::table('countries')->where('code', 'AU')->value('id');

        if (!$australiaId) {
            return;
        }

        // Orders created before NZ shopping-country rollout had no country recorded.
        // Treat them as Australia.
        DB::table('orders')
            ->whereNull('shopping_country_id')
            ->where('created_at', '<', '2026-07-17 00:00:00')
            ->update(['shopping_country_id' => $australiaId]);
    }

    public function down(): void
    {
        $australiaId = DB::table('countries')->where('code', 'AU')->value('id');

        if (!$australiaId) {
            return;
        }

        DB::table('orders')
            ->where('shopping_country_id', $australiaId)
            ->where('created_at', '<', '2026-07-17 00:00:00')
            ->update(['shopping_country_id' => null]);
    }
};
