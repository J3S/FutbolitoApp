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

class CrearJugadorTest extends TestCase
{
	use DatabaseTransactions;

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto de solo letras en el nombre del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 1.
     *
     * @return void
     */
    public function testCrearJugador1()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086777';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->press('Guardar')
            ->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                 'identificacion' => $identificacion,
                 'id_equipo' => $equipoL->id,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumérico en el nombre del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 2.
     *
     * @return void
     */
    public function testCrearJugador2()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086778';
        $apellidos = 'Oyola';
        $nombres = '1234';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));
            
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto solo letras en el apellido del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 3.
     *
     * @return void
     */
    public function testCrearJugador3()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086779';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->press('Guardar')
            ->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                 'identificacion' => $identificacion,
                 'id_equipo' => $equipoL->id,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumérico en el apellido del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 4.
     *
     * @return void
     */
    public function testCrearJugador4()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086779';
        $apellidos = '1234';
        $nombres = 'Pedro';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));
    }


     /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto 10 - 13 digitos en la identificacion del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 5.
     *
     * @return void
     */
    public function testCrearJugador5()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086780';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->press('Guardar')
            ->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                 'identificacion' => $identificacion,
                 'id_equipo' => $equipoL->id,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumerico en la identificacion del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 6.
     *
     * @return void
     */
    public function testCrearJugador6()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = 'hola';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto mo tiene entre 10 y 13 digitos la identificacion del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 7.
     *
     * @return void
     */
    public function testCrearJugador7()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '092';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));
    }

     /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto de la fecha del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 8.
     *
     * @return void
     */
    public function testCrearJugador8()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086781';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $fecha_nac = Carbon::create(1990, 3, 1);
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->select($fecha_nac->format('Y-m-d'), 'fecha_nac')
            ->press('Guardar')
            ->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                 'identificacion' => $identificacion,
                 'id_equipo' => $equipoL->id,
                 'fecha_nac' => $fecha_nac->format('Y-m-d')
                ]
            );
    }

     /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto de la fecha del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 9.
     *
     * @return void
     */
    public function testCrearJugador9()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086781';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $fecha_nac = "hola";
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->select($fecha_nac, 'fecha_nac')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto  solo letras del rol del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 10.
     *
     * @return void
     */
    public function testCrearJugador10()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086784';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $rol = 'Arquero';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->type($rol, 'rol')
            ->press('Guardar')
            ->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                 'identificacion' => $identificacion,
                 'rol' => $rol,
                 'id_equipo' => $equipoL->id,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumérico del rol del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 11.
     *
     * @return void
     */
    public function testCrearJugador11()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086785';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $rol = '1234Arquero';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->type($rol, 'rol')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto solo numero del peso del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 12.
     *
     * @return void
     */
    public function testCrearJugador12()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086789';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $rol = '1234Arquero';
        $peso = 80;
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->type($rol, 'rol')
            ->type($peso, 'peso')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumerico del peso del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 13.
     *
     * @return void
     */
    public function testCrearJugador13()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086789';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $rol = 'Arquero';
        $peso = '80rp';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->type($rol, 'rol')
            ->type($peso, 'peso')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto del email del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 14.
     *
     * @return void
     */
    public function testCrearJugador14()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086789';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $email = 'ajoyola@espol.edu.ec';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->type($email, 'email')
            ->press('Guardar')
            ->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                 'identificacion' => $identificacion,
                 'email' => $email,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto del email del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 15.
     *
     * @return void
     */
    public function testCrearJugador15()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086789';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $email = 'ajoyolaespol.edu.ec';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->type($email, 'email')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto solo dígitos del telefono del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 16.
     *
     * @return void
     */
    public function testCrearJugador16()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086789';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $telefono = 23456;
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->type($telefono, 'telefono')
            ->press('Guardar')
            ->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                 'identificacion' => $identificacion,
                 'telefono' => $telefono,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumerico del telefono del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 17.
     *
     * @return void
     */
    public function testCrearJugador17()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086789';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $telefono = '234guy';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->type($telefono, 'telefono')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));          
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto solo numero del numero de camiseta del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 18.
     *
     * @return void
     */
    public function testCrearJugador18()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086789';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $num_camiseta = 10;
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->type($num_camiseta, 'num_camiseta')
            ->press('Guardar')
            ->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                 'identificacion' => $identificacion,
                 'num_camiseta' => $num_camiseta,
                ]
            );   
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto alfanumerico del numero de camiseta del jugador.
     * Corresponde al caso de prueba testCrearJugador: post-condition 19.
     *
     * @return void
     */
    public function testCrearJugador19()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086789';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $num_camiseta = 'ho12';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->type($num_camiseta, 'num_camiseta')
            ->press('Guardar')
            ->seePageIs(route('jugador.create'));            
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto de categoria existente.
     * Corresponde al caso de prueba testCrearJugador: post-condition 20.
     *
     * @return void
     */
    public function testCrearJugador20()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $categorias = Categoria::where('nombre', 'Rey Master')->get();
        $categoriaL = $categorias[0];
        $identificacion = '0927086790';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->select($categoriaL->nombre, 'categoria')
            ->press('Guardar')
            ->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $nombres,
                 'apellidos' => $apellidos,
                 'identificacion' => $identificacion,
                 'categoria' => $categoriaL->nombre,
                ]
            );               
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto de categoria no existente.
     * Corresponde al caso de prueba testCrearJugador: post-condition 21.
     *
     * @return void
     */
    public function testCrearJugador21()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        // Se inicia una sesión para esta prueba
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $categoria = 'ESPOL TORNEO';
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'nombres' => 'Pedro',
            'apellidos' => 'Oyola',
            'identificacion' => '0927086790',
            'categoria' => $categoria,
            'id_equipo' => $equipoL->id,
        ];
        $response = $this->call('POST', 'jugador', $parametros);
        //$this->assertRedirectedToRoute('jugador.create');
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto de equipo existente.
     * Corresponde al caso de prueba testCrearJugador: post-condition 22.
     *
     * @return void
     */
    public function testCrearJugador22()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipos = Equipo::where('nombre', '20A')->get();
        $equipoL = $equipos[0];
        $identificacion = '0927086790';
        $apellidos = 'Oyola';
        $nombres = 'Pedro';
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->type($identificacion, 'identificacion')
            ->select($equipoL->id, 'equipo')
            ->press('Guardar') 
            ->seeInDatabase(
                    'jugadors',
                    [
                     'nombres' => $nombres,
                     'apellidos' => $apellidos,
                     'identificacion' => $identificacion,
                     'id_equipo' => $equipoL->id,
                    ]
                );           
    }

    /**
     * Comprueba el funcionamiento para crear un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso incorrecto de equipo no existente.
     * Corresponde al caso de prueba testCrearJugador: post-condition 23.
     *
     * @return void
     */
    public function testCrearJugador23()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'nombres' => 'Pedro',
            'apellidos' => 'Oyola',
            'identificacion' => '0927086790',
            'id_equipo' => '2000',
        ];
        $response = $this->call('POST', 'jugador', $parametros);
        //$this->assertRedirectedToRoute('jugador.create');      
    }




}
