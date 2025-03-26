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
        Schema::create('vat_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('vat_percent');
            $table->string('tax_percent');
            $table->boolean('publishStatus')->default(true);
            $table->string('created_year');
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
        Schema::dropIfExists('vat_taxes');
    }
};
