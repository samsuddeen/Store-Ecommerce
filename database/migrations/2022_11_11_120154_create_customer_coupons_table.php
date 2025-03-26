<?php

use App\Models\Coupon;
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
        Schema::create('customer_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Coupon::class);
            $table->integer('customer_id')->nullable();
            $table->boolean('is_expired')->default(0);
            $table->boolean('is_for_same')->default(0);
            $table->string('code')->unique();
            $table->enum('status', ['used_by_same', 'used'])->nullable();
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
        Schema::dropIfExists('customer_coupons');
    }
};
