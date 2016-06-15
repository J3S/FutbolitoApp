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
     * Se ingresan los datos del partido. Con todos los datos, requeridos y no requeridos.
     * Es exitoso si en la base de datos se encuentra el torneo con esos datos
     * registrado. 
     * Corresponde al caso de prueba testCrearPartido: post-condition 1.
     *
     * @return void
     */
    public function testCrearPartido1()
    {
	    $categoria = Categoria::where('nombre', "Rey Master")->first();
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
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Es exitoso si en la base de datos se encuentra el torneo con esos datos
     * registrado.
     * Corresponde al caso de prueba testCrearPartido: post-condition 2.
     *
     * @return void
     */
    public function testCrearPartido2()
    {
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

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Se ingresa un valor incorrecto "aaa" en el campo jornada.
     * Es exitoso si el sistema se mantiene en la pagina partido/create.
     * Corresponde al caso de prueba testCrearPartido: post-condition 3.
     *
     * @return void
     */
    public function testCrearPartido3()
    {
	    $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = "aaa";
        $lugar = "Cancha #3";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0];
        $equipoV = $equipos[1];
        $golLocal = 1;
        $golVisitante = 0;

        $this->visit(route('partido.create'))
            ->select($torneo->id, 'torneo')
            ->type($jornada, 'jornada')
            ->select($date, 'fecha')
            ->type($lugar, 'lugar')
            ->select($equipoL->id, 'equipo_local')
            ->select($equipoV->id, 'equipo_visitante')
            ->type($golLocal, 'gol_local')
            ->type($golVisitante, 'gol_visitante')
            ->press('Guardar')
            ->seePageIs(route('partido.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Se ingresa un valor incorrecto "aaa" en el campo fecha.
     * Es exitoso si el sistema se mantiene en la pagina partido/create.
     * Corresponde al caso de prueba testCrearPartido: post-condition 4.
     *
     * @return void
     */
    public function testCrearPartido4()
    {
	    $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = "aaa";
        $jornada = 1;
        $lugar = "Cancha #3";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0];
        $equipoV = $equipos[1];
        $golLocal = 1;
        $golVisitante = 0;

        $this->visit(route('partido.create'))
            ->select($torneo->id, 'torneo')
            ->type($jornada, 'jornada')
            ->select($date, 'fecha')
            ->type($lugar, 'lugar')
            ->select($equipoL->id, 'equipo_local')
            ->select($equipoV->id, 'equipo_visitante')
            ->type($golLocal, 'gol_local')
            ->type($golVisitante, 'gol_visitante')
            ->press('Guardar')
            ->seePageIs(route('partido.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Se ingresa un valor incorrecto "aaa" en el campo gol_local.
     * Es exitoso si el sistema se mantiene en la pagina partido/create.
     * Corresponde al caso de prueba testCrearPartido: post-condition 5.
     *
     * @return void
     */
    public function testCrearPartido5()
    {
	    $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0];
        $equipoV = $equipos[1];
        $golLocal = "aaa";
        $golVisitante = 0;

        $this->visit(route('partido.create'))
            ->select($torneo->id, 'torneo')
            ->type($jornada, 'jornada')
            ->select($date, 'fecha')
            ->type($lugar, 'lugar')
            ->select($equipoL->id, 'equipo_local')
            ->select($equipoV->id, 'equipo_visitante')
            ->type($golLocal, 'gol_local')
            ->type($golVisitante, 'gol_visitante')
            ->press('Guardar')
            ->seePageIs(route('partido.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Se ingresa un valor incorrecto "aaa" en el campo gol_visitante.
     * Es exitoso si el sistema se mantiene en la pagina partido/create.
     * Corresponde al caso de prueba testCrearPartido: post-condition 6.
     *
     * @return void
     */
    public function testCrearPartido6()
    {
	    $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0];
        $equipoV = $equipos[1];
        $golLocal = 1;
        $golVisitante = "aaa";

        $this->visit(route('partido.create'))
            ->select($torneo->id, 'torneo')
            ->type($jornada, 'jornada')
            ->select($date, 'fecha')
            ->type($lugar, 'lugar')
            ->select($equipoL->id, 'equipo_local')
            ->select($equipoV->id, 'equipo_visitante')
            ->type($golLocal, 'gol_local')
            ->type($golVisitante, 'gol_visitante')
            ->press('Guardar')
            ->seePageIs(route('partido.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Se ingresa un valor incorrecto 5000 (no existe ese ID) en el campo torneo.
     * Es exitoso si el sistema se mantiene en la pagina partido/create.
     * Corresponde al caso de prueba testCrearPartido: post-condition 7.
     *
     * @return void
     */
    public function testCrearPartido7()
    {
	    $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = 5000;
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0];
        $equipoV = $equipos[1];
        $golLocal = 1;
        $golVisitante = 0;

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
						'gol_local'        => $golLocal,
						'gol_visitante'    => $golVisitante,
				    ];
        $response = $this->call('POST', 'partido', $parametros);
        $this->assertRedirectedToRoute('partido.create');
    }

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Se ingresa un valor incorrecto 5000 (no existe ese ID) en el campo equipo_local.
     * Es exitoso si el sistema se mantiene en la pagina partido/create.
     * Corresponde al caso de prueba testCrearPartido: post-condition 8.
     *
     * @return void
     */
    public function testCrearPartido8()
    {
	    $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = 5000;
        $equipoV = $equipos[1]['id'];
        $golLocal = 1;
        $golVisitante = 0;

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL,
						'equipo_visitante' => $equipoV,
						'gol_local'        => $golLocal,
						'gol_visitante'    => $golVisitante,
				    ];
		//dd($parametros);
        $response = $this->call('POST', 'partido', $parametros);

        $this->assertRedirectedToRoute('partido.create');
    }

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Se ingresa un valor incorrecto 5000 (no existe ese ID) en el campo equipo_visitante.
     * Es exitoso si el sistema se mantiene en la pagina partido/create.
     * Corresponde al caso de prueba testCrearPartido: post-condition 9.
     *
     * @return void
     */
    public function testCrearPartido9()
    {
	    $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0]['id'];
        $equipoV = 5000;
        $golLocal = 1;
        $golVisitante = 0;

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL,
						'equipo_visitante' => $equipoV,
						'gol_local'        => $golLocal,
						'gol_visitante'    => $golVisitante,
				    ];
		//dd($parametros);
        $response = $this->call('POST', 'partido', $parametros);

        $this->assertRedirectedToRoute('partido.create');
    }

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Se ingresa un valor incorrecto (equipo de otra categoria) en el campo equipo_local.
     * Es exitoso si el sistema se mantiene en la pagina partido/create.
     * Corresponde al caso de prueba testCrearPartido: post-condition 10.
     *
     * @return void
     */
    public function testCrearPartido10()
    {
	    $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $equiposError = Equipo::where('estado', 1)->where('categoria', 'Junior')->get();
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equiposError[0]['id'];
        $equipoV = $equipos[0]['id'];
        $golLocal = 1;
        $golVisitante = 0;

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL,
						'equipo_visitante' => $equipoV,
						'gol_local'        => $golLocal,
						'gol_visitante'    => $golVisitante,
				    ];
		//dd($parametros);
        $response = $this->call('POST', 'partido', $parametros);

        $this->assertRedirectedToRoute('partido.create');
    }

    /**
     * Comprueba el funcionamiento para crear un partido.
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Se ingresa un valor incorrecto (equipo de otra categoria) en el campo equipo_visitante.
     * Es exitoso si el sistema se mantiene en la pagina partido/create.
     * Corresponde al caso de prueba testCrearPartido: post-condition 11.
     *
     * @return void
     */
    public function testCrearPartido11()
    {
	    $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2014)->first();
        $date = Carbon::create(2014, 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $equiposError = Equipo::where('estado', 1)->where('categoria', 'Junior')->get();
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0]['id'];
        $equipoV = $equiposError[0]['id'];
        $golLocal = 1;
        $golVisitante = 0;

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL,
						'equipo_visitante' => $equipoV,
						'gol_local'        => $golLocal,
						'gol_visitante'    => $golVisitante,
				    ];
		//dd($parametros);
        $response = $this->call('POST', 'partido', $parametros);

        $this->assertRedirectedToRoute('partido.create');
    }

}
