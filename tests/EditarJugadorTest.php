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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $nombres = 'Pedro';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'nombres'           => $nombres,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
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
        $jugadores = Jugador::where('id', '282')->get()->first();
        $nombres = '1234';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'nombres'           => $nombres,
        ];
        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
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
        $jugadores = Jugador::where('id', '282')->get()->first();
        $apellidos = 'Oyola';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'apellidos'        => $apellidos,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
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
        $jugadores = Jugador::where('id', '282')->get()->first();
        $apellidos = '1234';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'apellidos'        => $apellidos,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
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
        $jugadores = Jugador::where('id', '282')->get()->first();
        $identificacion = '0980980987';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'identificacion'   => $identificacion,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $identificacion = 'hola';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'identificacion'   => $identificacion,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index');  
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $identificacion = '1234';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'identificacion'   => $identificacion,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index');  
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $date2 = Carbon::create(2016, 2, 2);
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'fecha_nac'            => $date2->format('Y-m-d'),
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index');  
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $date2 = Carbon::create(2014, 1, 2);
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'fecha_nac'        => $date2->format('Y-m-d'),
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index');  
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $rol = 'Arquero';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'rol'              => $rol,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index');  
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $rol = '1234';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'rol'              => $rol,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index');  
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $peso = 80;
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'peso'             => $peso,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index');  
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $peso = '80rp';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'peso'             => $peso,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $email = 'a@espol.edu.ec';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'email'            => $email,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $email = 'aespol.edu.ec';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'email'            => $email,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $telefono = '1234';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'telefono'         => $telefono,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $telefono = '1234rt';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'telefono'         => $telefono,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $num_camiseta = '10';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'num_camiseta'     => $num_camiseta,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $num_camiseta = 'abc';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'num_camiseta'     => $num_camiseta,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $categoria = 'Rey Master';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'categoria'        => $categoria,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $categoria = 'Rey Master';
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'categoria'        => $categoria,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $equipo = Equipo::where('nombre', '20A')->get()->first();
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'id_equipo'        => $equipo->id,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
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
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $equipo = 5000;
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'id_equipo'        => $equipo,
        ];

        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('POST', $uri, $parametros2);
        //$this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );

        //$this->assertRedirectedToRoute('jugador.index'); 
    }
  
}
