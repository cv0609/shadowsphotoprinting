<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * When rule_apply_slug is set, only then: leave first product full price, rest get discount (percent or amount).
     */
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('rule_apply_slug', 100)->nullable()->after('qty')->comment('Category slug - when set, apply left-one-rest-discount for this category');
            $table->boolean('rule_leave_first')->default(true)->after('rule_apply_slug')->comment('Leave first product at full price');
            $table->string('rule_rest_discount_type', 20)->nullable()->after('rule_leave_first')->comment('percent or amount');
            $table->decimal('rule_rest_discount_value', 12, 2)->nullable()->after('rule_rest_discount_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['rule_apply_slug', 'rule_leave_first', 'rule_rest_discount_type', 'rule_rest_discount_value']);
        });
    }
};
