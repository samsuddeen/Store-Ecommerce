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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('model')->nullable();
            $table->integer('model_id')->nullable();


            $table->integer('position')->default(0);
            $table->string('banner_image')->nullable();

            $table->longText('content')->nullable();
            $table->string('external_link')->nullable();
            $table->boolean('status')->default(true);

            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('og_image')->nullable();

            $table->nestedSet();

            $table->enum('menu_type', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->nullable();
            $table->enum('show_on', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->nullable();
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
        Schema::dropIfExists('menus');
    }
};
