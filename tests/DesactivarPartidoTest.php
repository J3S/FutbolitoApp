<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Categoria;
use App\Equipo;
use App\Torneo;
use App\Partido;
use App\TorneoEquipo;
use Carbon\Carbon;
use App\Usuario;

class DesactivarPartidoTest extends TestCase
{

    /**
     * Comprueba el funcionamiento para desactivar un partido.
     * Se crea un partido con datos predeterminados. 
     * Se busca ese partido en la pagina principal de partido.
     * Se aplasta el boton para desactivar el partido.
     * Es exitoso si en la base de datos se encuentra el partido con el estado
     * cambiado a 0.
     * Corresponde al caso de prueba testDesactivarPartido: post-condition 1.
     *
     * @return void
     */
    public function testDesactivarPartido1()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0];
        $equipoV = $equipos[1];
        $golLocal = 1;
        $golVisitante = 0;

        // Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
        $registrosEliminados = Partido::where('id_torneo', $torneo->id)
                                ->where('fecha', $date->format('Y-m-d H:i:s'))
                                ->where('jornada', $jornada)
                                ->where('lugar', $lugar)
                                ->where('equipo_local', $equipoL->nombre)
                                ->where('equipo_visitante', $equipoV->nombre)
                                ->where('gol_local', $golLocal)
                                ->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
                        '_token'           => csrf_token(),
                        'torneo'           => $torneo->id,
                        'fecha'            => $date->format('Y-m-d H:i:s'),
                        'jornada'          => $jornada,
                        'lugar'            => $lugar,
                        'equipo_local'     => $equipoL->id,
                        'equipo_visitante' => $equipoV->id,
                        'gol_local'        => $golLocal,
                        'gol_visitante'    => $golVisitante,
                    ];
        $response = $this->call('POST', 'partido', $parametros);

        $partidoCreado = Partido::where('id_torneo', $torneo->id)
                                ->where('fecha', $date->format('Y-m-d H:i:s'))
                                ->where('jornada', $jornada)
                                ->where('lugar', $lugar)
                                ->where('equipo_local', $equipoL->nombre)
                                ->where('equipo_visitante', $equipoV->nombre)
                                ->where('gol_local', $golLocal)
                                ->where('gol_visitante', $golVisitante)
                                ->first();

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('DELETE', $uri, ['_token' => csrf_token()]);

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                 'estado' => 0,
                ]
            );

        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * Comprueba el funcionamiento para desactivar un partido.
     * Se trata de desactivar a un partido que no existe (prueba realizada por medio
     * de la url).
     * Es exitoso si permanece en la misma página principal de partido.
     * Corresponde al caso de prueba testEditarPartido: post-condition 2.
     *
     * @return void
     */
    public function testDesactivarPartido2()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        // Se inicia una sesión para esta prueba.
        Session::start();
        $partidoUltimoRegistro = Partido::orderBy('id', 'desc')->first();
        $idPartidoNoExistente = $partidoUltimoRegistro->id+1;

        $uri = "/partido/".$idPartidoNoExistente;
        $response = $this->call('DELETE', $uri, ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
