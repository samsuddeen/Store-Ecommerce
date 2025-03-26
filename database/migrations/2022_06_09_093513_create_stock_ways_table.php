<?php

use App\Models\CategoryAttribute;
use App\Models\ProductStock;
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
        Schema::create('stock_ways', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProductStock::class, 'stock_id');
            // $table->unsignedBigInteger('key');
            $table->foreignIdFor(CategoryAttribute::class, 'key');
            // $table->foreign('key')->references('id')->on('category_attributes')->onUpdate('cascade')->onDelete('cascade');
            $table->string('value')->nullable();
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
        Schema::dropIfExists('stock_ways');
    }
};
