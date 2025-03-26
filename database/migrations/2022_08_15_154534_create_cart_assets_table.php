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
        Schema::create('cart_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('product_name');
            $table->bigInteger('price');
            $table->bigInteger('qty');
            $table->bigInteger('sub_total_price');
            $table->string('color')->nullable();
            $table->bigInteger('discount')->nullable();
            $table->longText('options')->nullable();
            $table->boolean('is_ordered')->default(0);
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
        Schema::dropIfExists('cart_assets');
    }
};
