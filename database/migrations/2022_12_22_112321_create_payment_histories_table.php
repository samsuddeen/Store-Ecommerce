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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->string('from_model');
            $table->integer('from_id');
            $table->string('to_model');
            $table->integer('to_id');
            $table->string('reason_model');
            $table->integer('reason_id');
            $table->string('method_model')->nullable();
            $table->integer('method_id')->nullable();
            $table->integer('method')->nullable();
            $table->string('title');
            $table->longText('summary')->nullable();
            $table->string('url');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_received')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('payment_histories');
    }
};
