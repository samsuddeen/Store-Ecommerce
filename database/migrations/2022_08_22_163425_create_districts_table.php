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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('country_id')->constrained('countries','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('province')->constrained('provinces','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->bigInteger('dist_id')->unique()->unsigned();
            $table->string('np_name')->nullable();
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
        Schema::dropIfExists('districts');
    }
};
