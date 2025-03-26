<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Seller;
use App\Models\Order;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_order_cancels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_seller_order_id')->constrained('product_seller_orders','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('seller_order_id')->constrained('seller_orders','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('order_id')->constrained('orders','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->longText('reason')->nullable();
            $table->enum('cancel_status',[0,1,2])->default(0);
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
        Schema::dropIfExists('seller_order_cancels');
    }
};
