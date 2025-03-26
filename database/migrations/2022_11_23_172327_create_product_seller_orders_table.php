<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_seller_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('seller_order_id');
            $table->integer('product_id');
            $table->integer('qty');
            $table->double('price');
            $table->double('discount')->nullable();
            $table->double('sub_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_seller_orders');
    }
};
