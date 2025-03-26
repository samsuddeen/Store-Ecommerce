<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Country;
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
        Schema::create('products_back', function (Blueprint $table) {
            $table->id();
            $table->string('name', 450);
            $table->string('thumbnail', 450)->nullable();
            $table->string('slug', 450)->unique();
            $table->longText('description');
            $table->decimal('price', 12, 2);
            $table->decimal('retailPrice', 12, 2)->nullable()->default(0);
            $table->string('currency', 5)->default('NRS');
            $table->string('sku', 90);
            $table->string('keyword')->nullable()->comment('Provide Comma separate values to identify the products');
            $table->decimal('length', 10, 2)->default(0);
            $table->decimal('width', 10, 2)->default(0);
            $table->decimal('height', 10, 2)->default(0);
            $table->string('lengthUnit', 20);
            $table->decimal('weight', 10, 2)->default(0);
            $table->string('weightUnit', 20);
            $table->decimal('packSize')->default(0);
            $table->string('packSizeUnit', 5)->default(0);
            $table->decimal('packPerCarton')->default(0);
            $table->foreignIdFor(Brand::class)->nullable()->constrained();
            $table->foreignIdFor(Country::class)->nullable()->constrained();
            $table->foreignIdFor(Category::class)->nullable()->constrained();
            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->string('material', 50)->nullable();
            $table->decimal('rating', 2, 1, true)->default(5);
            $table->boolean('publishStatus')->default(true);
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
        Schema::dropIfExists('products_back');
    }
};
