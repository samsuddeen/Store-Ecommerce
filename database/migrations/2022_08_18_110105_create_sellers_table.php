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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->string('name');
            $table->string('phone', 20)->nullable()->unique();
            $table->string('email')->unique();
            $table->longText('verify_token')->nullable();
            $table->integer('verify_otp')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('province_id')->nullable();
            $table->string('district_id')->nullable();
            $table->boolean('is_new')->default(false);
            $table->string('address')->nullable();
            $table->enum('status', [1,2,3,4,5,6,7])->default(2);
            $table->string('photo', 400)->nullable();
            $table->string('area')->nullable();
            $table->integer('zip')->nullable();
            $table->string('company_name')->nullable();
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
        Schema::dropIfExists('sellers');
    }
};
