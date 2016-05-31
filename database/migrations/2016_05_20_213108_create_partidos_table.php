<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partidos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lugar', 200);
            $table->dateTime('fecha');
            $table->string('arbitro', 150)->nullable();
            $table->string('observacion', 150)->nullable();
            $table->integer('gol_visitante'); //cambio por el score
            $table->integer('gol_local');//cambio por el score
            $table->integer('id_equipoV')->unsigned(); //visitante
            $table->integer('id_equipo')->unsigned();
            $table->integer('id_torneo')->unsigned();
            $table->foreign('id_equipo')->references('id')->on('equipos');
            $table->foreign('id_equipoV')->references('id')->on('equipos');
            $table->foreign('id_torneo')->references('id')->on('torneos');
            $table->boolean('estado');
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
        Schema::drop('partidos');
    }
}
