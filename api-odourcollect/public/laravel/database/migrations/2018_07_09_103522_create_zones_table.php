<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('address')->nullable();
            $table->string('address__mapbox_id')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('postal_code__mapbox_id')->nullable();
            $table->string('place')->nullable();
            $table->string('place__mapbox_id')->nullable();
            $table->string('region')->nullable();
            $table->string('region__mapbox_id')->nullable();
            $table->string('country')->nullable();
            $table->string('country__mapbox_id')->nullable();
            $table->decimal('latitude', 11, 7)->default(0);
            $table->decimal('longitude', 11, 7)->default(0);
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
        Schema::dropIfExists('zones');
    }
}
