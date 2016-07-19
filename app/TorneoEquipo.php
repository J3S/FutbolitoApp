<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Equipo;

// Modelo para la tabla torneo_equipos en la base de datos
class TorneoEquipo extends Model
{
    //

    public function borrarPorAnio($anio)
    {
        $idsTorneoAnio = Torneo::where('anio', $anio)
                               ->get(['id']);
        foreach ($idsTorneoAnio as $idTorneoAnio) {
             TorneoEquipo::where('id_torneo', $idTorneoAnio->id)
                         ->delete();
        }
    }
}
