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
        Torneo::where('anio', $anio)->delete();
    }

    public static function getTorneoByCategoriaAndAnio($categoria, $anio) {
        return Torneo::where('id_categoria', Categoria::getId($categoria->nombre))
                            ->where('anio', $anio)
                            ->where('estado', 1)
                            ->first();
    } 
}

