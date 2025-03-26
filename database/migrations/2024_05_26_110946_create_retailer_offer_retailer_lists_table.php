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
        Schema::create('retailer_offer_retailer_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_offer_id')->constrained('retailer_offer_sections','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('retailer_id')->constrained('tbl_customers','id')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('retailer_offer_retailer_lists');
    }
};
