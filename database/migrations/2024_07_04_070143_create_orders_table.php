<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_session_id');
            $table->string('order_id');
            $table->string('order_number');
            $table->decimal('sub_total',10,2);
            $table->decimal('shipping_charge',10,2);
            $table->decimal('total',10,2);
            $table->string('payment_id');
            $table->string('is_paid');
            $table->enum('status',['0','1'])->default('0')->comment('0 for unpaid, 1 for paid');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
