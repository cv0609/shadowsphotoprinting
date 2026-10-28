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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'gift_card_id')) {
                $table->unsignedBigInteger('gift_card_id')->nullable()->after('coupon_id');
            }
            if (!Schema::hasColumn('orders', 'gift_card_code')) {
                $table->string('gift_card_code')->nullable()->after('coupon_code');
            }
            if (!Schema::hasColumn('orders', 'gift_card_discount')) {
                $table->decimal('gift_card_discount', 10, 2)->default(0)->after('discount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('orders', 'gift_card_id')) {
                $columnsToDrop[] = 'gift_card_id';
            }
            if (Schema::hasColumn('orders', 'gift_card_code')) {
                $columnsToDrop[] = 'gift_card_code';
            }
            if (Schema::hasColumn('orders', 'gift_card_discount')) {
                $columnsToDrop[] = 'gift_card_discount';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};

