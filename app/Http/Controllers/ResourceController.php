<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Torneo;
use App\TorneoEquipo;
use App\Equipo;
use App\Partido;
use App\Categoria;
use App\Jugador;

class ResourceController extends Controller
{
    public function getCategorias(){
        $categorias = Categoria::all()->toJson();
        return $categorias;
    }

    public function getTorneos(){
    	$torneos = Torneo::all()->toJson();
        return $torneos;
    }

    public function getTorneoEquipos(){
    	$torneoEquipos = TorneoEquipo::all()->toJson();
        return $torneoEquipos;
    }    

    public function getEquipos(){
        $equipos = Equipo::all()->toJson();
        return $equipos;
    }

    public function getPartidos(){
        $partidos = Partido::all()->toJson();
        return $partidos;
    }

    public function getJugadores(){
        $jugadores = Jugador::all()->toJson();
        return $jugadores;
    }
}
