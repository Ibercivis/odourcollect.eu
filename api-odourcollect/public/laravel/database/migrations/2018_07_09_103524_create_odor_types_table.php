<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOdorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odor_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_odor_parent_type')->unsigned()->default(0);
            $table->string('name');
            $table->string('slug');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_odor_parent_type')->references('id')->on('odor_parent_types')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odor_types');
    }
}
