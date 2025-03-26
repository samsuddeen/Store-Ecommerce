<?php

use App\Models\Customer\ReturnOrder;
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
        Schema::create('returned_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->foreignIdFor(ReturnOrder::class, 'return_id');
            $table->enum('status', [1,2,3,4,5,6,7])->default(1);
            $table->date('date')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('returned_statuses');
    }
};
