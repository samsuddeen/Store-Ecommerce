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
        Schema::create('countreys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country_slug')->unqiue();
            $table->string('iso_2')->nullable();
            $table->string('code')->nullable();
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->text('flags')->nullable();
            $table->string('country_zip')->nullable();
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
        Schema::dropIfExists('countreys');
    }
};
