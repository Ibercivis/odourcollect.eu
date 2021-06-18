<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_zones', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('zone_id')->unsigned();
			$table->integer('number_observations')->unsigned();
			$table->integer('hours')->unsigned();
			$table->integer('min_hedonic_tone');
			$table->integer('max_hedonic_tone');
			$table->integer('min_intensity')->unsigned();
			$table->integer('max_intensity')->unsigned();

			$table->timestamps();
            //$table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_zones');
    }
}
