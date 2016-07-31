<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Categoria;
use App\Equipo;
use App\Torneo;
use App\Jugador;
use App\Partido;
use App\TorneoEquipo;
use Carbon\Carbon;
use App\Usuario;

class EditarJugadorTest extends TestCase
{
	use DatabaseTransactions;

    /**
     * Comprueba el funcionamiento para editar un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto de solo letras en el nombre del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 1.
     *
     * @return void
     */
    public function testEditarJugador1()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $datonuevo = "PEDRO ADRIANO";
        $apellidos = "OYOLA SUAREZ";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'    => 'PUT',
            '_token'     => csrf_token(),
            'nombres'    => $datonuevo,
            'apellidos'  => $apellidos,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $datonuevo,
                 'apellidos' => $apellidos,
                ]
            );
    }

     /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumérico en el nombre del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 2.
     *
     * @return void
     */
    public function testEditarJugador2()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $datonuevo = "PEDRO123";
        $apellidos = "OYOLA SUAREZ";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'nombres'          => $datonuevo,
            'apellidos'        => $apellidos,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto solo letras en el apellido del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 3.
     *
     * @return void
     */
    public function testEditarJugador3()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $datonuevo = "OYOLA SANCHEZ";
        $apellidos = "OYOLA SUAREZ";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $datonuevo,
        ];
        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $datonuevo,
                ]
            );
    }


/**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumérico en el apellido del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 4.
     *
     * @return void
     */
    public function testEditarJugador4()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = "OYOLA123";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'apellidos'        => $datonuevo,
            'nombres'          => $nombres,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                ]
            );    
    }


     /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto 10 - 13 digitos en la identificacion del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 5.
     *
     * @return void
     */
    public function testEditarJugador5()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $identificacion = "1234567890";
        $datonuevo = "1234567895";
        $apellidos = "OYOLA SUAREZ";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->where('identificacion', $identificacion)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->where('identificacion', $identificacion)
                                ->first();
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $apellidos,
            'identificacion'   => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'        => $nombres,
                 'apellidos'      => $apellidos,
                 'identificacion' => $datonuevo,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumerico en la identificacion del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 6.
     *
     * @return void
     */
    public function testEditarJugador6()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $identificacion = "1234567890";
        $datonuevo = "als";
        $apellidos = "OYOLA SUAREZ";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->where('identificacion', $identificacion)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->where('identificacion', $identificacion)
                                ->first();
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $apellidos,
            'identificacion'   => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'        => $nombres,
                 'apellidos'      => $apellidos,
                 'identificacion' => $identificacion,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto mo tiene entre 10 y 13 digitos la identificacion del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 7.
     *
     * @return void
     */
    public function testEditarJugador7()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $identificacion = "1234567890";
        $datonuevo = "1234567";
        $apellidos = "OYOLA SUAREZ";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->where('identificacion', $identificacion)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->where('identificacion', $identificacion)
                                ->first();
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $apellidos,
            'identificacion'   => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'        => $nombres,
                 'apellidos'      => $apellidos,
                 'identificacion' => $identificacion,
                ]
            ); 
    }

     /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto de la fecha del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 8.
     *
     * @return void
     */
    public function testEditarJugador8()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = Carbon::create(1990, 3, 1);
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $apellidos,
            'fecha_nac'        => $datonuevo->format('Y-m-d'),
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'        => $nombres,
                 'apellidos'      => $apellidos,
                 'fecha_nac'      => $datonuevo->format('Y-m-d'),
                ]
            ); 
    }
     /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto de la fecha del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 9.
     *
     * @return void
     */
    public function testEditarJugador9()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = Carbon::create(19, 3, 1);
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $apellidos,
            'fecha_nac'        => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'        => $nombres,
                 'apellidos'      => $apellidos,
                ]
            ); 
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto  solo letras del rol del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 10.
     *
     * @return void
     */
    public function testEditarJugador10()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = "Arquero";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'    => 'PUT',
            '_token'     => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $apellidos,
            'rol'        => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'      => $nombres,
                 'apellidos'    => $apellidos,
                 'rol'          => $datonuevo,
                ]
            ); 
    }


    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumérico del rol del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 11.
     *
     * @return void
     */
    public function testEditarJugador11()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = "Arquero2134";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'    => 'PUT',
            '_token'     => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $apellidos,
            'rol'        => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'      => $nombres,
                 'apellidos'    => $apellidos,
                ]
            ); 
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto solo numero del peso del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 12.
     *
     * @return void
     */
    public function testEditarJugador12()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = 80;
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'    => 'PUT',
            '_token'     => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $apellidos,
            'peso'       => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'      => $nombres,
                 'apellidos'    => $apellidos,
                 'peso'         => $datonuevo,
                ]
            ); 
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumerico del peso del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 13.
     *
     * @return void
     */
    public function testEditarJugador13()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = "80rpty";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'    => 'PUT',
            '_token'     => csrf_token(),
            'nombres'    => $nombres,
            'apellidos'  => $apellidos,
            'peso'       => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'      => $nombres,
                 'apellidos'    => $apellidos,
                ]
            ); 
    }
    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto del email del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 14.
     *
     * @return void
     */
    public function testEditarJugador14()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = 'ajoyola@espol.edu.ec';
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'    => 'PUT',
            '_token'     => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $apellidos,
            'email'      => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'      => $nombres,
                 'apellidos'    => $apellidos,
                 'email'        => $datonuevo,
                ]
            ); 
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto del email del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 15.
     *
     * @return void
     */
    public function testEditarJugador15()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = 'ajoyolaespol.edu.ec';
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'    => 'PUT',
            '_token'     => csrf_token(),
            'nombres'          => $nombres,
            'apellidos'        => $apellidos,
            'email'      => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'      => $nombres,
                 'apellidos'    => $apellidos,
                ]
            ); 
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto solo dígitos del telefono del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 16.
     *
     * @return void
     */
    public function testEditarJugador16()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = '23456';
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'    => 'PUT',
            '_token'     => csrf_token(),
            'nombres'    => $nombres,
            'apellidos'  => $apellidos,
            'telefono'   => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'      => $nombres,
                 'apellidos'    => $apellidos,
                 'telefono'     => $datonuevo,
                ]
            ); 
    }
    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumerico del telefono del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 17.
     *
     * @return void
     */
    public function testEditarJugador17()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = "2abg3456";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'    => 'PUT',
            '_token'     => csrf_token(),
            'nombres'    => $nombres,
            'apellidos'  => $apellidos,
            'telefono'   => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'      => $nombres,
                 'apellidos'    => $apellidos,
                ]
            ); 
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto solo numero del numero de camiseta del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 18.
     *
     * @return void
     */
    public function testEditarJugador18()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = 10;
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'        => 'PUT',
            '_token'         => csrf_token(),
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'num_camiseta'   => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'       => $nombres,
                 'apellidos'     => $apellidos,
                 'num_camiseta'  => $datonuevo,
                ]
            ); 
    }
    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumerico del numero de camiseta del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 19.
     *
     * @return void
     */
    public function testEditarJugador19()
     {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = "aa10";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'        => 'PUT',
            '_token'         => csrf_token(),
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'num_camiseta'   => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'       => $nombres,
                 'apellidos'     => $apellidos,
                ]
            ); 
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto de categoria existente.
     * Corresponde al caso de prueba testEditarJugador: post-condition 20.
     *
     * @return void
     */
    public function testEditarJugador20()
     {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $categorias = Categoria::where('nombre', 'Rey Master')->get();
        $datonuevo = $categorias[0];
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'        => 'PUT',
            '_token'         => csrf_token(),
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'categoria'      => $datonuevo->nombre,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'    => $nombres,
                 'apellidos'  => $apellidos,
                ]
            ); 
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto de categoria no existente.
     * Corresponde al caso de prueba testEditarJugador: post-condition 21.
     *
     * @return void
     */
    public function testEditarJugador21()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = 'ESPOL TORNEO';
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'        => 'PUT',
            '_token'         => csrf_token(),
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'categoria'      => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'    => $nombres,
                 'apellidos'  => $apellidos,
                ]
            ); 
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto de equipo existente.
     * Corresponde al caso de prueba testEditarJugador: post-condition 22.
     *
     * @return void
     */
    public function testEditarJugador22()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $equipos = Equipo::where('nombre', '20A')->get();
        $datonuevo = $equipos[0];
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'        => 'PUT',
            '_token'         => csrf_token(),
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'id_equipo'      => $datonuevo->id,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'    => $nombres,
                 'apellidos'  => $apellidos,
                ]
            ); 
    }


    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto de equipo no existente.
     * Corresponde al caso de prueba testEditarJugador: post-condition 23.
     *
     * @return void
     */
    public function testEditarJugador23()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = "ADRIANO JOHNNY";
        $apellidos = "OYOLA SUAREZ";
        $datonuevo = '99999999';
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        $parametros2 = [
            '_method'        => 'PUT',
            '_token'         => csrf_token(),
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'id_equipo'      => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres'    => $nombres,
                 'apellidos'  => $apellidos,
                ]
            ); 
    }

}
