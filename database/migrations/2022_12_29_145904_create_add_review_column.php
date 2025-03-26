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
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('image')->constrained('reviews','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('seller_id')->nullable()->after('image')->constrained('users','id')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('seller_id');
        });
    }
};
