<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    public function borrarPorTorneoId($torneo)
    {
            Partido::where('id_torneo', $torneo->id)->delete();
    }
}
