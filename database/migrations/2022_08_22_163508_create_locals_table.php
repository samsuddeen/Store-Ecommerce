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
        Schema::create('locals', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('country_id')->constrained('countries','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('province_id')->constrained('provinces','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('dist_id')->constrained('districts','dist_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('local_level_id')->nullable();
            $table->float('local_id')->nullable();
            $table->string('local_name')->nullable();
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
        Schema::dropIfExists('locals');
    }
};
