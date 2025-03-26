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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('additional_address')->nullable();
            $table->string('address')->nullable();
            $table->boolean('status')->default(true);
            $table->string('photo', 400)->nullable();
            // $table->foreign('role_id')->references('id')->on('roles');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->longText('provider_id')->nullable();
            $table->longText('provider')->nullable();
            $table->longText('avatar')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
