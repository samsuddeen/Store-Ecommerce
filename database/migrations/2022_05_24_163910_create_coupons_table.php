<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->mediumText('summary')->nullable();
            $table->text('description')->nullable();
            $table->double('discount', 20, 2);
            $table->enum('is_percentage', ['yes', 'no'])->default('yes');
            $table->string('slug')->unique();
            $table->boolean('publishStatus')->default(true);
            $table->foreignIdFor(User::class)->nullable();
            $table->integer('currency_id')->nullable();
            $table->string('coupon_code')->unique();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
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
        Schema::dropIfExists('coupons');
    }
};
