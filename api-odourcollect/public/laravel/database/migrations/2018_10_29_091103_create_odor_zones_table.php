<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOdorZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odor_zones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_zone')->unsigned();
            $table->integer('id_odor')->unsigned();
            $table->boolean('verified')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_zone')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('id_odor')->references('id')->on('odors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odor_zones');
    }
}
