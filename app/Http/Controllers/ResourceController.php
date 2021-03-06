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

    public function getCategoria($id){
    	$categoria = Categoria::find($id)->toJson();
    	return $categoria;
    }

    public function getTorneos(){
        $array_torneos_x_anio = array();
        $categorias_torneo_anio = array();
        $anio;
        $torneos = Torneo::where('estado', 1)->orderBy('anio', 'desc')->orderBy('id_categoria', 'asc')->get();

        $anio = $torneos[0]->anio;
        foreach ($torneos as $torneo)  {
            $torneo_equipos = TorneoEquipo::where('id_torneo', $torneo->id)->get();
            if (count($torneo_equipos) > 0){
                if ($anio === $torneo->anio) {
                    $categoria = Categoria::where('id', $torneo->id_categoria)->first();
                    array_push($categorias_torneo_anio, $categoria->nombre);
                } else {
                    array_push($array_torneos_x_anio, array("anio" => $anio, "categorias" => $categorias_torneo_anio));
                    $anio = $torneo->anio;
                    $categorias_torneo_anio = array();
                    $categoria = Categoria::where('id', $torneo->id_categoria)->first();
                    array_push($categorias_torneo_anio, $categoria->nombre);
                }
            }
        }
        array_push($array_torneos_x_anio, array("anio" => $anio, "categorias" => $categorias_torneo_anio));
        return json_encode($array_torneos_x_anio);
    }

    public function getTorneo($id){
    	$torneo = Torneo::find($id)->toJson();
    	return $torneo;
    }

    public function getTorneoEquipos(){
    	$torneoEquipos = TorneoEquipo::all()->toJson();
        return $torneoEquipos;
    }

    public function getTorneoEquipo($id){
    	$torneoEquipo = TorneoEquipo::find($id)->toJson();
    	return $torneoEquipo;
    } 

    public function getEquipos(){
        $equipos = Equipo::where('estado', 1)->get()->toJson();
        return $equipos;
    }

    public function getEquipo($id){
		$equipo = Equipo::find($id)->toJson();
    	return $equipo;
    }

    public function getPartidos(){
        $partidos = Partido::where('estado', 1)->get()->toJson();
        return $partidos;
    }

    public function getPartido($id){
    	$partido = Partido::find($id)->toJson();
    	return $partido;
    }

    public function getJugadores(){
        $jugadores = Jugador::where('estado', 1)->get()->toJson();
        return $jugadores;
    }

    public function getJugador($id){
    	$jugador = Jugador::find($id)->toArray();
            $nombre_equipo = Equipo::where('id', $jugador['id_equipo'])->first(['nombre']);
            $data = ["info_jugador"=>$jugador, "nombre_equipo"=>$nombre_equipo];
    	return json_encode($data);
    }

    public function getAnioTorneos($anio){
    	$anioTorneos = [];
    	$torneos = Torneo::where('anio', $anio)->where('estado', 1)->orderBy('id_categoria', 'desc')->get();
    	foreach ($torneos as $torneo){
    		$categoria = Categoria::where('id', $torneo->id_categoria)->first();
    		array_push($anioTorneos, ["anio" => $torneo->anio, "categoria" => $categoria->nombre]);
    	}
    	return json_encode($anioTorneos);
    }

    public function getAniosConTorneos(){
    	$anios = [];
    	$torneos = Torneo::distinct()->select('anio')->where('estado', 1)->groupBy('anio')
    		->orderBy('anio', 'desc')->get();
    	foreach ($torneos as $torneo){
    		array_push($anios, ["anio" => $torneo->anio]);
    	}
    	return json_encode($anios);
    }

    public function getPartidosJornada($torneo, $jornada){
    	$torneo = Torneo::find($torneo);
    	$partidos = Partido::where('id_torneo', $torneo->id)->where('jornada', $jornada)->get();
    	return $partidos->toJson();
    }

    public function getTablaPosicionesTorneo($id){
    	$torneo = Torneo::find($id);
    	$categoria = Categoria::find($torneo->id_categoria);
    	$partidos = Partido::where('id_torneo', $torneo->id)->get();
    	$torneoEquipos = TorneoEquipo::where('id_torneo', $id)->get();
    	$equipos = Equipo::where('estado', 1)->get();
    	$equiposTorneo = [];
        $equiposTorneoId = [];
    	$resultados = [];

    	foreach ($torneoEquipos as $torneoEquipo) {
    		foreach  ($equipos as $equipoTorneo) {
				if ($equipoTorneo->id == $torneoEquipo->id_equipo) {
    				array_push ($equiposTorneo, $equipoTorneo->nombre);
                    array_push ($equiposTorneoId, $equipoTorneo->id);
    			}
    		}
		}
        $index = 0;
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
				"GC"=>$gc, "GD"=>$gd, "PTS"=>$pts, "ID"=>$equiposTorneoId[$index]]);
                                    $index++;
		}

		// ordeno los resultados por puntos y gol diferencia
		foreach ($resultados as $key => $row) {
		    $puntos[$key]  = $row['PTS'];
		    $goldif[$key] = $row['GD'];
            $golfav[$key] = $row['GF'];
		}
        $tabla_posiciones = [];
        if(count($resultados) > 0){
            array_multisort($puntos, SORT_DESC, $goldif, SORT_DESC, $resultados, SORT_DESC, $golfav);
            $tabla_posiciones = ["categoria" => $categoria->nombre, "anio" => $torneo->anio, "resultados" => $resultados];
        }
		
		return json_encode($tabla_posiciones);
	}

    public function getTablasPosicionesAnio($anio){
    	$tablas_posiciones = [];
    	$torneos = Torneo::where('estado', 1)->where('anio', $anio)->orderBy('id_categoria', 'asc')->get();
    	foreach($torneos as $torneo) {
    		$tabla_posiciones = json_decode($this->getTablaPosicionesTorneo($torneo->id));
            if(count($tabla_posiciones) > 0){
                array_push($tablas_posiciones, $tabla_posiciones);
            }	
    	}
		return json_encode($tablas_posiciones);
	}

    public function getPartidosTorneo($id){
        $torneo = Torneo::find($id);
        $partidos = Partido::where('id_torneo', $torneo->id)->get();
        return $partidos->toJson();
    }


    public function getUltimos10PartidosEquipo($id){
        $equipo = Equipo::find($id);
        $partidos = Partido::where('equipo_local', $equipo->nombre)->orwhere('equipo_visitante', $equipo->nombre)->orderBy('fecha', 'desc')->take(10)->get();
        $partidosxtorneo = [];
        $info_partidos = [];
        $id_torneo = 0;
        foreach ($partidos as $partido) {
            $torneo = Torneo::getTorneoById($partido->id_torneo);
            if ($id_torneo == 0) {
                $id_torneo = $torneo->id;    
            }
            if($id_torneo == $torneo->id) {
                array_push($partidosxtorneo, $partido->toArray());
            }
            else {
                $group_torneo = Torneo::getTorneoById($id_torneo);
                $categoria_nombre = Categoria::getNombre($group_torneo->id_categoria)->nombre;
                $nombre_torneo = $categoria_nombre . ' ' . $group_torneo->anio;
                 array_push($info_partidos, ["nombre_torneo"=>$nombre_torneo, "partidos" => $partidosxtorneo]);

                 $id_torneo = $torneo->id;
                 $partidosxtorneo = array();
                 array_push($partidosxtorneo, $partido->toArray());
            }
        }
        $group_torneo = Torneo::getTorneoById($id_torneo);
        $categoria_nombre = Categoria::getNombre($group_torneo->id_categoria)->nombre;
        $nombre_torneo = $categoria_nombre . ' ' . $group_torneo->anio;
        array_push($info_partidos, ["nombre_torneo"=>$nombre_torneo, "partidos" => $partidosxtorneo]);
        return json_encode($info_partidos);
    }
    
    public function getJugadoresEquipo ($id_equipo) {
        $jugadoresEquipo = [];
        $jugadores = Jugador::getJugadoresxEquipo($id_equipo);
        foreach ($jugadores as $jugador) {
            array_push($jugadoresEquipo, array(
                'id' => $jugador->id,
                'nombre' => $jugador->nombres,
                'apellido' => $jugador->apellidos,
                'rol' => $jugador->rol,
                'camiseta' => $jugador->num_camiseta
                ));
        }
        return json_encode($jugadoresEquipo);
    }

    public function getUltimaTablaEquipo($id){
        $torneos = Torneo::where('estado', 1)->orderBy('anio', 'desc')->get();
        $torneoEquipos = TorneoEquipo::where('id_equipo', $id)->get();
        $torneoParticipacion = [];
        $count = 0;
        foreach($torneos as $torneo){
            foreach($torneoEquipos as $torneoEquipo){
                if($torneoEquipo->id_torneo === $torneo->id && $count === 0){
                    array_push($torneoParticipacion, $torneo);
                    $count = 1;
                }
            }
        }
        $tablaParticipacion = [];
        if($count === 1){
            $tablaParticipacion = json_decode($this->getTablaPosicionesTorneo($torneoParticipacion[0]->id));
        }
        return json_encode($tablaParticipacion);
    }

}
