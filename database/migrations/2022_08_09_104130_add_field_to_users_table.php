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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company')->after('additional_address')->nullable();
            $table->string('agree')->after('company')->nullable();
            $table->string('gender')->after('agree')->nullable();
            $table->string('country')->after('gender')->nullable();
            $table->string('area')->after('country')->nullable();
            $table->string('member_id')->after('area')->nullable();
            $table->string('province')->after('member_id')->nullable();
            $table->string('district')->after('province')->nullable();
            $table->integer('zip')->after('district')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company');
            $table->dropColumn('agree');
            $table->dropColumn('gender');
            $table->dropColumn('country');
            $table->dropColumn('area');
            $table->dropColumn('member_id');
        });
    }
};
