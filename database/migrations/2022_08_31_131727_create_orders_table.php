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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tbl_customers','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('shipping_charge')->nullable(true);//aff id
            $table->integer('material_charge')->nullable(true);//aff id
            $table->integer('total_quantity')->nullable(true);
            $table->integer('total_price')->nullable(true);
            $table->string('ref_id')->nullable(true);
            $table->double('total_discount')->nullable(true);
            $table->string('coupon_name')->nullable(true);
            // $table->string('coupon_discount_price')->nullable(true);
            // $table->string('coupon_code')->nullable(true);
            $table->boolean('pending')->default(1);
            $table->boolean('ready_to_ship')->default(0);
            $table->boolean('payment_status')->default(0);
            $table->boolean('deleted_by_customer')->default(0);
            $table->boolean('failed_delivery')->default(0);
            $table->string('merchant_name')->nullable();
            $table->string('payment_with')->nullable();
            $table->longText('transaction_ref_id')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('approved')->default(0);

            //shipping address
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('area')->nullable();
            $table->string('additional_address')->nullable();
            $table->string('zip')->nullable();

            //billing_address
            $table->string('b_name');
            $table->string('b_email');
            $table->string('b_phone')->nullable();
            $table->string('b_province')->nullable();
            $table->string('b_district')->nullable();
            $table->string('b_area')->nullable();
            $table->string('b_additional_address')->nullable();
            $table->string('b_zip')->nullable();

            // $table->enum('status', [1,2,3,4,5,6,7])->default(1);
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
        Schema::dropIfExists('orders');
    }
};
