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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 450);
            $table->string('slug', 450)->unique();
            $table->longText('short_description')->nullable();
            $table->longText('long_description')->nullable();

            $table->string('min_order', 450)->nullable();
            $table->string('returnable_time', 450)->nullable();
            $table->string('delivery_time', 450)->nullable();

            $table->string('keyword')->nullable()->comment('Provide Comma separate values to identify the products');
            $table->decimal('package_weight', 10, 2)->nullable();
            $table->decimal('dimension_length', 10, 2)->nullable();
            $table->decimal('dimension_width', 10, 2)->nullable();
            $table->decimal('dimension_height', 10, 2)->nullable();
            $table->string('warranty_type')->nullable();
            $table->string('warranty_period')->nullable();
            $table->mediumText('warranty_policy')->nullable();
            $table->foreignIdFor(Brand::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Country::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Category::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->decimal('rating', 2, 1, true)->default(5);
            $table->boolean('publishStatus')->default(true);
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return voidet
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
