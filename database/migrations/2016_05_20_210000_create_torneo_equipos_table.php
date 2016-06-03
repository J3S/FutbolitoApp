<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTorneoEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torneo_equipos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_equipo')->unsigned();
            $table->foreign('id_equipo')->references('id')->on('equipos');
            $table->integer('id_torneo')->unsigned();
            $table->foreign('id_torneo')->references('id')->on('torneos');
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
        Schema::dropIfExists('torneo_equipos');
    }
}
