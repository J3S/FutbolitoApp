<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Categoria;

class Equipo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'equipos';

    public static function getEquipoDivididoxCategorias () {
        /*
            * Variable equiposxcategorias - Contiene a los equipos registrados
            * divididos por categorías.
        */
        $equiposxcategorias = [];

        // División de los equipos por categorías.
        foreach (Categoria::getAll() as $categoria) {
            $equiposxcategorias[$categoria->nombre] = Equipo::getEquiposxCategoria($categoria);
        }

        return $equiposxcategorias;
    }

    public static function getEquiposxCategoria($categoria) {
        return Equipo::where('categoria', $categoria->nombre)->get();
    }
}
