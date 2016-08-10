<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{

    public static function  getJugadoresxEquipo ($id_equipo) {
        return Jugador::where('id_equipo', $id_equipo)->orderBy('num_camiseta', 'asc')->get();
    }

}
