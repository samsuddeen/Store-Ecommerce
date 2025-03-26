<?php

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
        Schema::create('return_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->foreignId('user_id')->constrained('tbl_customers','id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('product_id');
            $table->integer('order_asset_id');
            $table->integer('qty')->default(1);
            $table->string('amount');
            $table->longText('reason');
            $table->longText('comment');
            $table->enum('status', [1,2,3,4,5])->default(1);
            $table->boolean('is_new')->default(true);
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
        Schema::dropIfExists('return_orders');
    }
};
