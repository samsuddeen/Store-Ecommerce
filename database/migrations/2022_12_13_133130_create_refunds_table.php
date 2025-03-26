<?php

use App\Models\Customer\ReturnOrder;
use App\Models\New_Customer;
use App\Models\User;
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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(ReturnOrder::class,'return_id');
            $table->foreignIdFor(New_Customer::class, 'user_id')->nullable();
            $table->boolean('is_new')->default(true);
            $table->enum('status', [1,2,3,4,5])->default(1);
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
        Schema::dropIfExists('refunds');
    }
};
