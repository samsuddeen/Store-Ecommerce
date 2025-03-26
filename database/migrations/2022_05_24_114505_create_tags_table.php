<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->mediumText('summary')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('image');
            $table->string('thumbnail');
            $table->string('slug')->unique();
            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->integer('order')->default(0);
            $table->boolean('publishStatus')->default(true);
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
        Schema::dropIfExists('tags');
    }
};
