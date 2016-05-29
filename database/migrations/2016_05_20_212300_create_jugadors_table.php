<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJugadorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jugadors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres', 50);
            $table->string('apellidos', 50);
            $table->integer('edad');
            $table->string('identificacion', 10);
            $table->string('rol', 30);
            $table->string('email', 80);
            $table->string('telefono', 30);
            $table->double('peso');
            $table->boolean('estado');
            $table->boolean('puedeJugar');
            $table->integer('id_equipo')->unsigned()->nullable();
            $table->foreign('id_equipo')->references('id')->on('equipos');
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
        Schema::dropIfExists('jugadors');
    }
}
