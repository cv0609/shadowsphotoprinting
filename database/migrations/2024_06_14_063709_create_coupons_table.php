<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->increments('id');
                $table->enum("object_type", ['0','1'])->default("0");
                $table->integer("vendor_id")->nullable();
                $table->string('code');
                $table->enum("used", ['0','1']);
                $table->enum("type", ['0','1'])->comment('0 for amount, 1 for percent')->default("0");
                $table->double('amount', 12, 2);
                $table->double('minimum_spend', 12, 2)->nullable();
                $table->double('maximum_spend', 12, 2)->nullable();
                $table->date('start_date');
                $table->date('end_date');
                $table->string('products')->nullable();
                $table->enum("auto_applied", ['0','1'])->comment('0 for not auto applied, 1 for auto applied')->default("0");
                $table->integer("use_limit")->nullable();
                $table->integer("same_ip_limit")->nullable();
                $table->integer("use_limit_per_user")->nullable();
                $table->string("use_device")->nullable();
                $table->enum("multiple_use", ['0','1'])->comment('0 for No, 1 for Yes')->default("0");
                $table->integer("total_use")->default(0);
                $table->integer("status")->default(0);
                $table->enum("is_active", ['0','1'])->comment('0 for No, 1 for Yes')->default("1");
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
