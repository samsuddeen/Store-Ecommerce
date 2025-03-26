<?php

use App\Models\User;
use App\Models\Refund\Refund;
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
        Schema::create('refund_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->foreignIdFor(Refund::class, 'refund_id')->constrained();
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
        Schema::dropIfExists('refund_statuses');
    }
};
