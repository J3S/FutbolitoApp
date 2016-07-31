<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Partido;

// Modelo para la tabla torneos en la base de datos
class Torneo extends Model
{
    public function borrarPorAnio($anio)
    {
        $idsTorneoAnio = Torneo::where('anio', $anio)
                               ->get(['id']);
        foreach ($idsTorneoAnio as $idTorneoAnio) {
            $partido = new Partido();
            $partido->borrarPorTorneoId($idTorneoAnio);
        }
    }
}
