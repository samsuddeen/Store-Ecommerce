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
        Schema::table('product_seller_orders', function (Blueprint $table) {
            $table->string('discount_percent')->after('sub_total')->nullable();
            $table->integer('order_id')->after('sub_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_seller_orders', function (Blueprint $table) {
            $table->dropColumn('discount_percent');
            $table->dropColumn('order_id');
        });
    }
};
