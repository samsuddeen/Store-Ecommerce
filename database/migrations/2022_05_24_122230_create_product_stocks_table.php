<?php

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained();
            // $table->foreignIdFor(ProductSize::class)->nullable()->constrained();
            // $table->foreignIdFor(Color::class)->nullable()->constrained();
            $table->foreignIdFor(Color::class, 'color_id')->nullable();
            $table->integer('price');
            $table->integer('special_price')->nullable();
            $table->timestamp('special_from')->nullable();
            $table->timestamp('special_to')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('free_items')->nullable();
            $table->mediumText('sellersku')->nullable();
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
        Schema::dropIfExists('product_stocks');
    }
};
