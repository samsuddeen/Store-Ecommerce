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
        Schema::create('delivery_charges', function (Blueprint $table) {
            $table->id();
            $table->integer('logistic_id')->nullable();
            $table->integer('delivery_route_id')->nullable();
            $table->double('branch_delivery', 20, 2);
            $table->double('branch_express_delivery', 20, 2)->nullable();
            $table->double('branch_normal_delivery', 20, 2)->nullable();
            $table->double('door_delivery', 20, 2);
            $table->double('door_express_delivery', 20, 2); 
            $table->double('door_normal_delivery', 20, 2)->nullable();
            $table->string('currency', 5)->default('NRS');
            $table->foreignIdFor(User::class)->nullable()->constrained();
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
        Schema::dropIfExists('delivery_charges');
    }
};
