<?php

use App\Models\Payout\Payout;
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
        Schema::create('payout_statuses', function (Blueprint $table) {
            $table->id();
            $table->integer('updated_by')->nullable();
            $table->foreignIdFor(Payout::class);
            $table->enum('status', [1,2,3,4,5])->default(1);
            $table->timestamp('date');
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
        Schema::dropIfExists('payout_statuses');
    }
};
