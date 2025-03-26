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
        Schema::create('user_payment_ids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tbl_customers','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->longText('payment_bill_id')->nullable();
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
        Schema::dropIfExists('user_payment_ids');
    }
};
