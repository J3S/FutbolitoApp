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
            $table->boolean('estado');
            $table->dateTime('fecha');
            $table->string('arbitro', 150)->nullable();
            $table->string('observacion', 150)->nullable();
            $table->string('equipo_local', 50);
            $table->string('equipo_visitante', 50);
            $table->integer('gol_visitante');
            $table->integer('gol_local');
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
        Schema::dropIfExists('partidos');
    }
}
