<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{

    public static function  getJugadoresxEquipo ($id_equipo) {
        return Jugador::where('id_equipo', $id_equipo)->orderBy('rol', 'asc')->get();
    }

}
