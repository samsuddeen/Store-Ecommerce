<?php

use App\Models\New_Customer;
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
        Schema::create('user_share_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(New_Customer::class, 'share_from')->nullable();
            $table->foreignIdFor(New_Customer::class, 'share_to')->nullable();
            $table->bigInteger('points');
            $table->string('referal_code');
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
        Schema::dropIfExists('user_share_lists');
    }
};
