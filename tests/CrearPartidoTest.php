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

class CrearPartidoTest extends TestCase
{
	use DatabaseTransactions;

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido.
     * Es exitoso si en la base de datos se encuentra el torneo con esos datos
     * registrado.
     * Corresponde al caso de prueba testCrearPartido: post-condition 1.
     *
     * @return void
     */
    public function testCrearPartido1()
    {
	    $categoria = Categoria::find(1);
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $observacion = "No hay observaciones.";
        $arbitro = "John Doe";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0];
        $equipoV = $equipos[1];
        $golLocal = 1;
        $golVisitante = 0;

        $this->visit(route('partido.create'))
            ->select($torneo->id, 'torneo')
            ->type($jornada, 'jornada')
            ->type($arbitro, 'arbitro')
            ->select($date->format('Y-m-d H:i:s'), 'fecha')
            ->type($lugar, 'lugar')
            ->type($observacion, 'observaciones')
            ->select($equipoL->id, 'equipo_local')
            ->select($equipoV->id, 'equipo_visitante')
            ->type($golLocal, 'gol_local')
            ->type($golVisitante, 'gol_visitante')
            ->press('Guardar')
            ->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'arbitro' => $arbitro,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'observacion' => $observacion,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido.
     * Es exitoso si en la base de datos se encuentra el torneo con esos datos
     * registrado.
     * Corresponde al caso de prueba testCrearPartido: post-condition 2.
     *
     * @return void
     */
    public function testCrearPartido2()
    {
	    $categoria = Categoria::find(1);
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $observacion = "No hay observaciones.";
        $arbitro = "John Doe";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0];
        $equipoV = $equipos[1];
        $golLocal = 1;
        $golVisitante = 0;

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
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

        $this->seeInDatabase(
            'partidos',
            [
             'id_torneo' 	    => $torneo->id,
			 'fecha'            => $date->format('Y-m-d H:i:s'),
			 'jornada'          => $jornada,
			 'lugar'            => $lugar,
			 'equipo_local'     => $equipoL->nombre,
			 'equipo_visitante' => $equipoV->nombre,
			 'gol_local'        => $golLocal,
			 'gol_visitante'    => $golVisitante,
            ]
        );
    }
}
