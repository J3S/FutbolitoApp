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

class EditarPartidoTest extends TestCase
{

	use DatabaseTransactions;

    /**
     * Comprueba el funcionamiento de la vista para editar un partido.
     *
     * @return void
     */
    public function testEditarPartidoView(){
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $response = $this->call('GET', 'partido/1/edit');
        $this->assertEquals(200, $response->status());
    }

    /**
     * Comprueba el funcionamiento de la vista para editar un partido que no existe.
     *
     * @return void
     */
    public function testEditarPartidoNoExiste(){
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $partidoUltimoRegistro = Partido::orderBy('id', 'desc')->first();
        $idUltimoRegistro = $partidoUltimoRegistro->id+1;
        $this->visit('partido/' . $idUltimoRegistro . '/edit')->seePageIs(route('partido.index'));
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Es exitoso si en la base de datos se encuentra el partido con esos datos
     * modificados y se redirige a la vista principal de partido.
     * Corresponde al caso de prueba testEditarPartido: post-condition 1.
     *
     * @return void
     */
    public function testEditarPartido1()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

    	// Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
    	$registrosEliminados = Partido::where('id_torneo', $torneo->id)
        						->where('fecha', $date->format('Y-m-d H:i:s'))
        						->where('jornada', $jornada)
        						->where('lugar', $lugar)
        						->where('equipo_local', $equipoL->nombre)
        						->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)
        						->first();

	    $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $estado2 = 1;
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method' 		   => 'PUT',
            '_token' 		   => csrf_token(),
			'torneo'           => $torneo2->id,
			'fecha'            => $date2->format('Y-m-d H:i:s'),
			'jornada'          => $jornada2,
			'lugar'            => $lugar2,
			'equipo_local'     => $equipoL2->id,
			'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado2,
			'gol_local'        => $golLocal2,
			'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo2->id,
                 'jornada' => $jornada2,
                 'fecha' => $date2->format('Y-m-d H:i:s'),
                 'lugar' => $lugar2,
                 'equipo_local' => $equipoL2->nombre,
                 'equipo_visitante' => $equipoV2->nombre,
                 'estado' => $estado2,
                 'gol_local' => $golLocal2,
                 'gol_visitante' => $golVisitante2,
                ]
            );

        $this->assertRedirectedToRoute('partido.index');

    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa valor invalido "aaa" en campo jornada.
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 2.
     *
     * @return void
     */
    public function testEditarPartido2()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

    	// Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
    	$registrosEliminados = Partido::where('id_torneo', $torneo->id)
        						->where('fecha', $date->format('Y-m-d H:i:s'))
        						->where('jornada', $jornada)
        						->where('lugar', $lugar)
        						->where('equipo_local', $equipoL->nombre)
        						->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)
        						->first();

	    $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = "aaa";
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method' 		   => 'PUT',
            '_token' 		   => csrf_token(),
			'torneo'           => $torneo2->id,
			'fecha'            => $date2->format('Y-m-d H:i:s'),
			'jornada'          => $jornada2,
			'lugar'            => $lugar2,
			'equipo_local'     => $equipoL2->id,
			'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
			'gol_local'        => $golLocal2,
			'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa valor invalido "aaa" en campo jornada.
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 3.
     *
     * @return void
     */
    public function testEditarPartido3()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

    	// Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
    	$registrosEliminados = Partido::where('id_torneo', $torneo->id)
        						->where('fecha', $date->format('Y-m-d H:i:s'))
        						->where('jornada', $jornada)
        						->where('lugar', $lugar)
        						->where('equipo_local', $equipoL->nombre)
        						->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)
        						->first();

	    $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = "aaa";
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method' 		   => 'PUT',
            '_token' 		   => csrf_token(),
			'torneo'           => $torneo2->id,
			'fecha'            => $date2,
			'jornada'          => $jornada2,
			'lugar'            => $lugar2,
			'equipo_local'     => $equipoL2->id,
			'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
			'gol_local'        => $golLocal2,
			'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa valor invalido "aaa" en campo gol_local.
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 4.
     *
     * @return void
     */
    public function testEditarPartido4()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

    	// Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
    	$registrosEliminados = Partido::where('id_torneo', $torneo->id)
        						->where('fecha', $date->format('Y-m-d H:i:s'))
        						->where('jornada', $jornada)
        						->where('lugar', $lugar)
        						->where('equipo_local', $equipoL->nombre)
        						->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)
        						->first();

	    $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $golLocal2 = "aaa";
        $golVisitante2 = 1;
        $parametros2 = [
            '_method' 		   => 'PUT',
            '_token' 		   => csrf_token(),
			'torneo'           => $torneo2->id,
			'fecha'            => $date2->format('Y-m-d H:i:s'),
			'jornada'          => $jornada2,
			'lugar'            => $lugar2,
			'equipo_local'     => $equipoL2->id,
			'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
			'gol_local'        => $golLocal2,
			'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
            );
    }


    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa valor invalido "aaa" en campo gol_visitante.
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 5.
     *
     * @return void
     */
    public function testEditarPartido5()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

    	// Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
    	$registrosEliminados = Partido::where('id_torneo', $torneo->id)
        						->where('fecha', $date->format('Y-m-d H:i:s'))
        						->where('jornada', $jornada)
        						->where('lugar', $lugar)
        						->where('equipo_local', $equipoL->nombre)
        						->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)
        						->first();

	    $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $golLocal2 = 3;
        $golVisitante2 = "aaa";
        $parametros2 = [
            '_method' 		   => 'PUT',
            '_token' 		   => csrf_token(),
			'torneo'           => $torneo2->id,
			'fecha'            => $date2->format('Y-m-d H:i:s'),
			'jornada'          => $jornada2,
			'lugar'            => $lugar2,
			'equipo_local'     => $equipoL2->id,
			'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
			'gol_local'        => $golLocal2,
			'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa un valor incorrecto 5000 (no existe ese ID) en el campo torneo.
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 6.
     *
     * @return void
     */
    public function testEditarPartido6()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

    	// Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
    	$registrosEliminados = Partido::where('id_torneo', $torneo->id)
        						->where('fecha', $date->format('Y-m-d H:i:s'))
        						->where('jornada', $jornada)
        						->where('lugar', $lugar)
        						->where('equipo_local', $equipoL->nombre)
        						->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)
        						->first();

	    $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = 5000;
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method' 		   => 'PUT',
            '_token' 		   => csrf_token(),
			'torneo'           => $torneo2,
			'fecha'            => $date2->format('Y-m-d H:i:s'),
			'jornada'          => $jornada2,
			'lugar'            => $lugar2,
			'equipo_local'     => $equipoL2->id,
			'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
			'gol_local'        => $golLocal2,
			'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa un valor incorrecto 5000 (no existe ese ID) en el campo equipo_local.
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 7.
     *
     * @return void
     */
    public function testEditarPartido7()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

    	// Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
    	$registrosEliminados = Partido::where('id_torneo', $torneo->id)
        						->where('fecha', $date->format('Y-m-d H:i:s'))
        						->where('jornada', $jornada)
        						->where('lugar', $lugar)
        						->where('equipo_local', $equipoL->nombre)
        						->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)
        						->first();

	    $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = 5000;
        $equipoV2 = $equipos2[1];
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method' 		   => 'PUT',
            '_token' 		   => csrf_token(),
			'torneo'           => $torneo2->id,
			'fecha'            => $date2->format('Y-m-d H:i:s'),
			'jornada'          => $jornada2,
			'lugar'            => $lugar2,
			'equipo_local'     => $equipoL2,
			'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
			'gol_local'        => $golLocal2,
			'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa un valor incorrecto 5000 (no existe ese ID) en el campo equipo_visitante.
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 8.
     *
     * @return void
     */
    public function testEditarPartido8()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

    	// Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
    	$registrosEliminados = Partido::where('id_torneo', $torneo->id)
        						->where('fecha', $date->format('Y-m-d H:i:s'))
        						->where('jornada', $jornada)
        						->where('lugar', $lugar)
        						->where('equipo_local', $equipoL->nombre)
        						->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)
        						->first();

	    $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = 5000;
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method' 		   => 'PUT',
            '_token' 		   => csrf_token(),
			'torneo'           => $torneo2->id,
			'fecha'            => $date2->format('Y-m-d H:i:s'),
			'jornada'          => $jornada2,
			'lugar'            => $lugar2,
			'equipo_local'     => $equipoL2->id,
			'equipo_visitante' => $equipoV2,
            'estado'           => $estado,
			'gol_local'        => $golLocal2,
			'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa un valor incorrecto (equipo de otra categoria) en el campo equipo_local.
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 9.
     *
     * @return void
     */
    public function testEditarPartido9()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

    	// Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
    	$registrosEliminados = Partido::where('id_torneo', $torneo->id)
        						->where('fecha', $date->format('Y-m-d H:i:s'))
        						->where('jornada', $jornada)
        						->where('lugar', $lugar)
        						->where('equipo_local', $equipoL->nombre)
        						->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)
        						->first();

	    $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equiposError = Equipo::where('estado', 1)->where('categoria', 'Junior')->get();
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equiposError[0];
        $equipoV2 = $equipos2[0];
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method' 		   => 'PUT',
            '_token' 		   => csrf_token(),
			'torneo'           => $torneo2->id,
			'fecha'            => $date2->format('Y-m-d H:i:s'),
			'jornada'          => $jornada2,
			'lugar'            => $lugar2,
			'equipo_local'     => $equipoL2->id,
			'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
			'gol_local'        => $golLocal2,
			'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa un valor incorrecto (equipo de otra categoria) en el campo equipo_visitante.
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 10.
     *
     * @return void
     */
    public function testEditarPartido10()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

    	// Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
    	$registrosEliminados = Partido::where('id_torneo', $torneo->id)
        						->where('fecha', $date->format('Y-m-d H:i:s'))
        						->where('jornada', $jornada)
        						->where('lugar', $lugar)
        						->where('equipo_local', $equipoL->nombre)
        						->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)->delete();

        Session::start();
        $parametros = [
				        '_token' 		   => csrf_token(),
						'torneo'           => $torneo->id,
						'fecha'            => $date->format('Y-m-d H:i:s'),
						'jornada'          => $jornada,
						'lugar'            => $lugar,
						'equipo_local'     => $equipoL->id,
						'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
        						->where('gol_local', $golLocal)
        						->where('gol_visitante', $golVisitante)
        						->first();

	    $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equiposError = Equipo::where('estado', 1)->where('categoria', 'Junior')->get();
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equiposError[0];
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method' 		   => 'PUT',
            '_token' 		   => csrf_token(),
			'torneo'           => $torneo2->id,
			'fecha'            => $date2->format('Y-m-d H:i:s'),
			'jornada'          => $jornada2,
			'lugar'            => $lugar2,
			'equipo_local'     => $equipoL2->id,
			'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
			'gol_local'        => $golLocal2,
			'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa el mismo equipo como local y visitante (no permitido).
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 11.
     *
     * @return void
     */
    public function testEditarPartido11()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

        // Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
        $registrosEliminados = Partido::where('id_torneo', $torneo->id)
                                ->where('fecha', $date->format('Y-m-d H:i:s'))
                                ->where('jornada', $jornada)
                                ->where('lugar', $lugar)
                                ->where('equipo_local', $equipoL->nombre)
                                ->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
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
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
                                ->where('gol_local', $golLocal)
                                ->where('gol_visitante', $golVisitante)
                                ->first();

        $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[0];
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'torneo'           => $torneo2->id,
            'fecha'            => $date2->format('Y-m-d H:i:s'),
            'jornada'          => $jornada2,
            'lugar'            => $lugar2,
            'equipo_local'     => $equipoL2->id,
            'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
            'gol_local'        => $golLocal2,
            'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
        );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa una fecha no permitida (anterior al año del torneo seleccionado).
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 12.
     *
     * @return void
     */
    public function testEditarPartido12()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

        // Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
        $registrosEliminados = Partido::where('id_torneo', $torneo->id)
                                ->where('fecha', $date->format('Y-m-d H:i:s'))
                                ->where('jornada', $jornada)
                                ->where('lugar', $lugar)
                                ->where('equipo_local', $equipoL->nombre)
                                ->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
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
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
                                ->where('gol_local', $golLocal)
                                ->where('gol_visitante', $golVisitante)
                                ->first();

        $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2016)->first();
        $date2 = Carbon::create(2014, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'torneo'           => $torneo2->id,
            'fecha'            => $date2->format('Y-m-d H:i:s'),
            'jornada'          => $jornada2,
            'lugar'            => $lugar2,
            'equipo_local'     => $equipoL2->id,
            'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
            'gol_local'        => $golLocal2,
            'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
        );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa una fecha no permitida (posterior al año del torneo seleccionado).
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 13.
     *
     * @return void
     */
    public function testEditarPartido13()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

        // Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
        $registrosEliminados = Partido::where('id_torneo', $torneo->id)
                                ->where('fecha', $date->format('Y-m-d H:i:s'))
                                ->where('jornada', $jornada)
                                ->where('lugar', $lugar)
                                ->where('equipo_local', $equipoL->nombre)
                                ->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
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
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
                                ->where('gol_local', $golLocal)
                                ->where('gol_visitante', $golVisitante)
                                ->first();

        $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2015)->first();
        $date2 = Carbon::create(2016, 2, 2, 10, 0, 0);
        $jornada2 = 2;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'torneo'           => $torneo2->id,
            'fecha'            => $date2->format('Y-m-d H:i:s'),
            'jornada'          => $jornada2,
            'lugar'            => $lugar2,
            'equipo_local'     => $equipoL2->id,
            'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
            'gol_local'        => $golLocal2,
            'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
        );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa una jornada inválida, mayor al límite establecido (100).
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 14.
     *
     * @return void
     */
    public function testEditarPartido14()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

        // Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
        $registrosEliminados = Partido::where('id_torneo', $torneo->id)
                                ->where('fecha', $date->format('Y-m-d H:i:s'))
                                ->where('jornada', $jornada)
                                ->where('lugar', $lugar)
                                ->where('equipo_local', $equipoL->nombre)
                                ->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
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
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
                                ->where('gol_local', $golLocal)
                                ->where('gol_visitante', $golVisitante)
                                ->first();

        $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2014)->first();
        $date2 = Carbon::create(2014, 2, 2, 10, 0, 0);
        $jornada2 = 5000;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $golLocal2 = 3;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'torneo'           => $torneo2->id,
            'fecha'            => $date2->format('Y-m-d H:i:s'),
            'jornada'          => $jornada2,
            'lugar'            => $lugar2,
            'equipo_local'     => $equipoL2->id,
            'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
            'gol_local'        => $golLocal2,
            'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
        );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa goles locales inválido, mayor al límite establecido (100).
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 15.
     *
     * @return void
     */
    public function testEditarPartido15()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

        // Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
        $registrosEliminados = Partido::where('id_torneo', $torneo->id)
                                ->where('fecha', $date->format('Y-m-d H:i:s'))
                                ->where('jornada', $jornada)
                                ->where('lugar', $lugar)
                                ->where('equipo_local', $equipoL->nombre)
                                ->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
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
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
                                ->where('gol_local', $golLocal)
                                ->where('gol_visitante', $golVisitante)
                                ->first();

        $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2014)->first();
        $date2 = Carbon::create(2014, 2, 2, 10, 0, 0);
        $jornada2 = 1;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $golLocal2 = 5000;
        $golVisitante2 = 1;
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'torneo'           => $torneo2->id,
            'fecha'            => $date2->format('Y-m-d H:i:s'),
            'jornada'          => $jornada2,
            'lugar'            => $lugar2,
            'equipo_local'     => $equipoL2->id,
            'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
            'gol_local'        => $golLocal2,
            'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
        );
    }

    /**
     * Comprueba el funcionamiento para editar un partido.
     * Se crea un partido con datos predeterminados.
     * Se edita ese partido recién creado modificándole los campos obligatorios
     * (torneo, jornada, equipos, lugar, fecha y goles) y se manda a actualizar.
     * Se ingresa una goles visitante inválido, mayor al límite establecido (100).
     * Es exitoso si en la base de datos se encuentra el partido con los datos
     * originales sin ser modificados y se mantiene en la misma pagina.
     * Corresponde al caso de prueba testEditarPartido: post-condition 16.
     *
     * @return void
     */
    public function testEditarPartido16()
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
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

        // Si existe algun partido con los mismos datos que el partido que vamos a crear, se lo elimina.
        $registrosEliminados = Partido::where('id_torneo', $torneo->id)
                                ->where('fecha', $date->format('Y-m-d H:i:s'))
                                ->where('jornada', $jornada)
                                ->where('lugar', $lugar)
                                ->where('equipo_local', $equipoL->nombre)
                                ->where('equipo_visitante', $equipoV->nombre)
                                ->where('estado', $estado)
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
                        'estado'           => $estado,
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
                                ->where('estado', $estado)
                                ->where('gol_local', $golLocal)
                                ->where('gol_visitante', $golVisitante)
                                ->first();

        $categoria2 = Categoria::where('nombre', "Master")->first();
        $torneo2 = Torneo::where('id_categoria', $categoria2->id)->where('anio', 2014)->first();
        $date2 = Carbon::create(2014, 2, 2, 10, 0, 0);
        $jornada2 = 1;
        $lugar2 = "Cancha #2";
        $equipos2 = Equipo::where('estado', 1)->where('categoria', $categoria2->nombre)->get();
        $equipoL2 = $equipos2[0];
        $equipoV2 = $equipos2[1];
        $golLocal2 = 3;
        $golVisitante2 = 5000;
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'torneo'           => $torneo2->id,
            'fecha'            => $date2->format('Y-m-d H:i:s'),
            'jornada'          => $jornada2,
            'lugar'            => $lugar2,
            'equipo_local'     => $equipoL2->id,
            'equipo_visitante' => $equipoV2->id,
            'estado'           => $estado,
            'gol_local'        => $golLocal2,
            'gol_visitante'    => $golVisitante2,
        ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros2);

        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date->format('Y-m-d H:i:s'),
                 'lugar' => $lugar,
                 'equipo_local' => $equipoL->nombre,
                 'equipo_visitante' => $equipoV->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
        );
    }

     /**
     * Comprueba el funcionamiento para editar un partido.
     * Se ingresan los datos del partido. Solo con datos requeridos.
     * Se ingresa fecha, hora y lugar del partido para que generen una colisión de horarios.
     * Existe colisión de horarios si existe un partido hasta 59 minutos antes o despues en el mismo lugar.
     * Corresponde al caso de prueba testEditarPartido: post-condition 17.
     *
     * @return void
     */
    public function testEditarPartido17()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $categoria = Categoria::where('nombre', "Rey Master")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', 2016)->first();
        $date = Carbon::create(2016, 1, 1, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #1";
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0];
        $equipoV = $equipos[1];
        $estado = 1;
        $golLocal = 0;
        $golVisitante = 0;
        Session::start();
        $parametros = [
                        '_token'           => csrf_token(),
                        'torneo'           => $torneo->id,
                        'fecha'            => $date->format('Y-m-d H:i:s'),
                        'jornada'          => $jornada,
                        'lugar'            => $lugar,
                        'equipo_local'     => $equipoL->id,
                        'equipo_visitante' => $equipoV->id,
                        'estado'           => $estado,
                        'gol_local'        => $golLocal,
                        'gol_visitante'    => $golVisitante,
                    ];
        $response = $this->call('POST', 'partido', $parametros);

        $date2 = Carbon::create(2016, 1, 1, 12, 55, 0);
        $lugar2 = "Cancha #2";
        $equipoL2 = $equipos[2];
        $equipoV2 = $equipos[3];
        $parametros2 = [
                        '_token'           => csrf_token(),
                        'torneo'           => $torneo->id,
                        'fecha'            => $date2->format('Y-m-d H:i:s'),
                        'jornada'          => $jornada,
                        'lugar'            => $lugar2,
                        'equipo_local'     => $equipoL2->id,
                        'equipo_visitante' => $equipoV2->id,
                        'estado'           => $estado,
                        'gol_local'        => $golLocal,
                        'gol_visitante'    => $golVisitante,
                    ];
        $response = $this->call('POST', 'partido', $parametros2);

        $partidoCreado = Partido::where('id_torneo', $torneo->id)
                                ->where('fecha', $date2->format('Y-m-d H:i:s'))
                                ->where('jornada', $jornada)
                                ->where('lugar', $lugar2)
                                ->where('equipo_local', $equipoL2->nombre)
                                ->where('equipo_visitante', $equipoV2->nombre)
                                ->where('estado', $estado)
                                ->where('gol_local', $golLocal)
                                ->where('gol_visitante', $golVisitante)
                                ->first();

        $parametros3 = [
                        '_method'          => 'PUT',
                        '_token'           => csrf_token(),
                        'torneo'           => $torneo->id,
                        'fecha'            => $date2->format('Y-m-d H:i:s'),
                        'jornada'          => $jornada,
                        'lugar'            => $lugar,
                        'equipo_local'     => $equipoL2->id,
                        'equipo_visitante' => $equipoV2->id,
                        'estado'           => $estado,
                        'gol_local'        => $golLocal,
                        'gol_visitante'    => $golVisitante,
                    ];

        $uri = "/partido/".$partidoCreado->id;
        $response = $this->call('POST', $uri, $parametros3);
        
        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
                'partidos',
                [
                 'id_torneo' => $torneo->id,
                 'jornada' => $jornada,
                 'fecha' => $date2->format('Y-m-d H:i:s'),
                 'lugar' => $lugar2,
                 'equipo_local' => $equipoL2->nombre,
                 'equipo_visitante' => $equipoV2->nombre,
                 'estado'           => $estado,
                 'gol_local' => $golLocal,
                 'gol_visitante' => $golVisitante,
                ]
        );
    }
}
