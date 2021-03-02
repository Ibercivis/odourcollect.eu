<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOdorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_odor_type')->unsigned();
            $table->integer('id_user')->unsigned();
            $table->boolean('verified')->default(1);
            $table->string('status')->default('published');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('origin')->nullable();
            $table->integer('color')->default(1);
            //$table->integer('id_zone')->unsigned()->default(0);
            $table->integer('id_odor_intensity')->unsigned();
            $table->integer('id_odor_duration')->unsigned()->default(0);
            $table->integer('id_odor_annoy')->unsigned();
            $table->dateTime('published_at');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_odor_type')->references('id')->on('odor_types')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('id_zone')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('id_odor_intensity')->references('id')->on('odor_intensities')->onDelete('cascade');
            $table->foreign('id_odor_duration')->references('id')->on('odor_durations')->onDelete('cascade');
            $table->foreign('id_odor_annoy')->references('id')->on('odor_annoys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odors');
    }
}
