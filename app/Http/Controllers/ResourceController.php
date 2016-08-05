<?php
/**
 * Controlador para recursos Android
 *
 * @category   PHP
 * @package    Laravel
 * @subpackage Controller
 * @author     Kevin Filella <kfl0202@gmail.com>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
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

/**
 * Clase ResourceController
 *
 * @category   PHP
 * @package    Laravel
 * @subpackage Controller
 * @author     Kevin Filella <kfl0202@gmail.com>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
class ResourceController extends Controller
{
    public function getCategorias(){
        $categorias = Categoria::all()->toJson();
        return $categorias;
    }

    public function getTorneos(){
    	$torneos = Torneo::where('estado', 1)->get()->toJson();
        return $torneos;
    }

    public function getTorneoEquipos(){
    	$torneoEquipos = TorneoEquipo::all()->toJson();
        return $torneoEquipos;
    }    

    public function getEquipos(){
        $equipos = Equipo::where('estado', 1)->get()->toJson();
        return $equipos;
    }

    public function getPartidos(){
        $partidos = Partido::where('estado', 1)->get()->toJson();
        return $partidos;
    }

    public function getJugadores(){
        $jugadores = Jugador::where('estado', 1)->get()->toJson();
        return $jugadores;
    }

    public function getTablaPosicionesTorneo($id){
    	$torneo = Torneo::where('id', $id)->first();
    	$partidos = Partido::where('id_torneo', $torneo->id)->get();
    	$torneoEquipos = TorneoEquipo::where('id_torneo', $id)->get();
    	$equipos = Equipo::where('estado', 1)->get();
    	$equiposTorneo = [];
    	$resultados = [];

    	foreach ($torneoEquipos as $torneoEquipo) {
    		foreach  ($equipos as $equipoTorneo) {
				if ($equipoTorneo->id == $torneoEquipo->id_equipo) {
    				array_push ($equiposTorneo, $equipoTorneo->nombre);
    			}
    		}
		}

		foreach ($equiposTorneo as $equipo) {
			$pj = $pg = $pe = $pp = $gf = $gc = $gd = $pts = 0;

			foreach ($partidos as $partido) {
				// equipo es local y gano el partido
				if ($partido->equipo_local == $equipo && $partido->gol_local > $partido->gol_visitante){
					$pg += 1;
					$pts += 3;
					$gf += $partido->gol_local;
					$gc += $partido->gol_visitante;
					$gd += ($partido->gol_local - $partido->gol_visitante);
					$pj += 1;
				}
				// equipo es visitante y gano el partido
				if ($partido->equipo_visitante == $equipo && $partido->gol_visitante > $partido->gol_local){
					$pg += 1;
					$pts += 3;
					$gf += $partido->gol_visitante;
					$gc += $partido->gol_local;
					$gd += ($partido->gol_visitante - $partido->gol_local);
					$pj += 1;
				}
				// equipo es local o visitante y empato el partido
				if (($partido->equipo_local == $equipo || $partido->equipo_visitante == $equipo) && $partido->gol_local == $partido->gol_visitante){
					$pe += 1;
					$pts += 1;
					if($partido->equipo_local == $equipo){
						$gf += $partido->gol_local;
						$gc += $partido->gol_visitante;
					}
					if($partido->equipo_visitante == $equipo){
						$gf += $partido->gol_visitante;
						$gc += $partido->gol_local;
					}
					$pj += 1;
				}
				// equipo es local y perdio el partido
				if ($partido->equipo_local == $equipo && $partido->gol_local < $partido->gol_visitante){
					$pp += 1;
					$gf += $partido->gol_local;
					$gc += $partido->gol_visitante;
					$gd += ($partido->gol_local - $partido->gol_visitante);
					$pj += 1;
				}
				// equipo es visitante y perdio el partido
				if ($partido->equipo_visitante == $equipo && $partido->gol_visitante < $partido->gol_local){
					$pp += 1;
					$gf += $partido->gol_visitante;
					$gc += $partido->gol_local;
					$gd += ($partido->gol_visitante - $partido->gol_local);
					$pj += 1;
				}
			}
			array_push($resultados, ["equipo"=>$equipo, "PJ"=>$pj, "PG"=>$pg, "PE"=>$pe, "PP"=>$pp, "GF"=>$gf,
				"GC"=>$gc, "GD"=>$gd, "PTS"=>$pts]);
		}

		// ordeno los resultados por puntos y gol diferencia
		foreach ($resultados as $key => $row) {
		    $puntos[$key]  = $row['PTS'];
		    $goldif[$key] = $row['GD'];
		}
		array_multisort($puntos, SORT_DESC, $goldif, SORT_DESC, $resultados);

		return json_encode($resultados);
	}
}
