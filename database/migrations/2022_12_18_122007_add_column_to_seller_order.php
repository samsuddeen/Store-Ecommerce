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
        Schema::table('seller_orders', function (Blueprint $table) {            
            $table->boolean('is_new')->default(true)->after('total');
            $table->enum('status', [1,2,3,4,5,6,7,8,9])->default(1)->after('is_new');
            $table->enum('payment_status', [0,1,2,3,4,5,6,7,8])->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return voidclear
     */
    public function down()
    {
        Schema::table('seller_orders', function (Blueprint $table) {
            //
        });
    }
};
