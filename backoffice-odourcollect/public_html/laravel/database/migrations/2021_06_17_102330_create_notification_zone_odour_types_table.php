<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationZoneOdourTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_zone_odour_types', function (Blueprint $table) {
            $table->increments('id');

			$table->integer('id_odour_type')->unsigned();
			$table->integer('id_notification_zone')->unsigned();
			
            $table->timestamps();

			//$table->foreign('id_odour_type')->references('id')->on('odor_types')->onDelete('cascade');
			$table->foreign('id_notification_zone')->references('id')->on('notification_zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_zone_odour_types');
    }
}
