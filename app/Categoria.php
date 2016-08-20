<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
    public static function getAll() {
        return Categoria::all();
    }

    public static function getId($nombre) {
        return Categoria::where('nombre', $nombre)->first(['id'])->id;
    }

    public static function getNombre($id) {
        return Categoria::where('id', $id)->first(['nombre']);
    }
}
