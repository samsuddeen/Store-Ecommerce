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
        Schema::create('tbl_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->unique();
            $table->string('address')->nullable();
            $table->enum('status', [0, 1, 2, 3, 4, 5, 6])->default(0);
            $table->string('photo', 400)->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('area')->nullable();
            $table->integer('zip')->nullable();
            $table->string('company_name')->nullable();
            $table->longText('verify_token')->nullable();
            $table->integer('verify_otp')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // $table->date('deleted_at')->nullable();
            $table->rememberToken();
            $table->bigInteger('votes')->nullable()->default(12);
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
        Schema::table('tbl_customers', function (Blueprint $table) {
            // $table->dropColumn('phone');
            // $table->string('phone', 20)->unique();
        });
    }
};
