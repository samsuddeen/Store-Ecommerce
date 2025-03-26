<?php

use App\Models\Local;
use App\Models\Location;
use App\Models\Admin\Hub\Hub;
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
        Schema::create('hub_near_places', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Hub::class)->constrained();
            $table->foreignIdFor(City::class)->nullable();
            $table->foreignIdFor(Location::class)->nullable();
            $table->boolean('is_location')->default(1);
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
        Schema::dropIfExists('hub_near_places');
    }
};
