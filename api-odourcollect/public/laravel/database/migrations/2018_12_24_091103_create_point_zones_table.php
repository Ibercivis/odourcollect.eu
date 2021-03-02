<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_of_interest_zones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_zone')->unsigned();
            $table->integer('id_point_of_interest')->unsigned();
            $table->boolean('verified')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_zone')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('id_point_of_interest')->references('id')->on('points_of_interest')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_of_interest_zones');
    }
}
