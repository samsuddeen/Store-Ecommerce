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
        Schema::table('product_cities', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_cities', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }
};
