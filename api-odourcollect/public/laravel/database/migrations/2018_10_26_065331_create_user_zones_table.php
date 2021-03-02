<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_zones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_zone')->unsigned();
            $table->integer('id_user')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_zone')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_zones');
    }
}
