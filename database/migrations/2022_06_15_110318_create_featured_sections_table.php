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
        Schema::create('featured_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->string('slug')->nullable()->unique();
            $table->integer('count')->default(3);
            $table->boolean('status')->default(1);
            $table->enum('type', [1,2,3,4,5,6])->default(1);
            $table->string('meta_title')->nullable();
            $table->mediumText('meta_keywords')->nullable();
            $table->mediumText('meta_description')->nullable();
            $table->integer('order')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('featured_sections');
    }
};
