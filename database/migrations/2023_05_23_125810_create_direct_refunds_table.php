<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\New_Customer;
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
        Schema::create('direct_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(New_Customer::class, 'user_id')->nullable();
            $table->foreignIdFor(Order::class, 'order_id')->nullable();
            $table->boolean('is_new')->default(0);
            $table->enum('status',[1,2,3])->default(1);
            $table->longText('refund_details')->nullable();
            $table->string('paid_from')->nullable();
            $table->string('paid_by')->nullable();
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('direct_refunds');
    }
};
