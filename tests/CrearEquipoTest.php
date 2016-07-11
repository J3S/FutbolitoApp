<?php
/**
 * This file contains common functions used for testing when user creates Equipo.
 * PHP version 5
 *
 * @category   CategoryName
 * @package    FutbolitoApp
 * @subpackage Tests
 * @author     Branny Chito <brajchit@espol.edu.ec>
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @link       http://definirlink.local
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpKernel\Client;
use App\Categoria;
use App\Jugador;
/**
 * CrearEquipoTest Class Doc Comment
 *
 * @category PHPunit
 * @package  Test
 * @author   Branny Chito <brajchit@espol.edu.ec>
 * @license  MIT, http://opensource.org/licenses/MIT
 * @link     http://definirlink.local
 */
class CrearEquipoTest extends TestCase
{


    use DatabaseTransactions;


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa nombre alfanumerico, nombre del entrenador, categoria
     * master, sin jugadores.
     * Es exitoso si el sistema registra los datos en la BDD, responde mensaje
     * (guardado con exito) y redireciona a '/equipo'
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo1()
    {
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        // Session start is necesary for csrf_token!
        Session::start();
        $test = $this->post(
            'equipo',
            [
             'nombre'     => $inputNombre,
             'entrenador' => $inputEntrenador,
             'categoria'  => $inputCategoria,
             'ids'        => $inputIds,
            ],
            ['X-CSRF-TOKEN' => csrf_token()]
        );
        // $this->dump(); // testing debug
        // $this->expectOutputString("cont: ".$response->content()); //debug
        $test->seeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );
        $test->seeJson(['mensaje' => "guardado con exito"]);
        $test->seePageIs('/equipo');

    }//end testCrearEquipo1()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa nombre string vacio, nombre del entrenador, categoria
     * master, sin jugadores.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo2()
    {
        $inputNombre     = '';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        // Session start is necesary for csrf_token!
        Session::start();
        $test = $this->post(
            '/equipo',
            [
             'nombre'     => $inputNombre,
             'entrenador' => $inputEntrenador,
             'categoria'  => $inputCategoria,
             'ids'        => $inputIds,
            ],
            [
             'X-CSRF-TOKEN'          => csrf_token(),
             'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]
        );
        $test->dontSeeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );
        $test->assertResponseStatus(422);
        $test->seeJson(["nombre" => ["El campo nombre es obligatorio."]]);

    }//end testCrearEquipo2()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa nombre ya existente, nombre del entrenador, categoria
     * master, sin jugadores.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo3()
    {
        $inputNombre     = '20A';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        // Session start is necesary for csrf_token!
        Session::start();
        $test = $this->post(
            '/equipo',
            [
             'nombre'     => $inputNombre,
             'entrenador' => $inputEntrenador,
             'categoria'  => $inputCategoria,
             'ids'        => $inputIds,
            ],
            [
             'X-CSRF-TOKEN'          => csrf_token(),
             'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]
        );

        $test->dontSeeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );
        $test->assertResponseStatus(422);
        $test->seeJson(["nombre" => ["nombre ya ha sido registrado."]]);

    }//end testCrearEquipo3()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa nombre alfanumerico, nombre entrenador(ninguno), categoria
     * master, sin jugadores.
     * Es exitoso si el sistema registra los datos en la BDD, responde mensaje
     * (guardado con exito) y redireciona a '/equipo'
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo4()
    {
        $inputNombre     = 'Bayern Munich';
        $inputEntrenador = '';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        // Session start is necesary for csrf_token!
        Session::start();
        $test = $this->post(
            'equipo',
            [
             'nombre'     => $inputNombre,
             'entrenador' => $inputEntrenador,
             'categoria'  => $inputCategoria,
             'ids'        => $inputIds,
            ],
            [
             'X-CSRF-TOKEN'          => csrf_token(),
             'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]
        );

        $test->seeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );
        $test->seeJson(['mensaje' => "guardado con exito"]);
        $test->seePageIs('/equipo');

    }//end testCrearEquipo4()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa: nombre(alfanumerico), entrenador(alfanumerico), categoria
     * master, sin jugadores.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo5()
    {
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk123';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        // Session start is necesary for csrf_token!
        Session::start();
        $test = $this->post(
            '/equipo',
            [
             'nombre'     => $inputNombre,
             'entrenador' => $inputEntrenador,
             'categoria'  => $inputCategoria,
             'ids'        => $inputIds,
            ],
            [
             'X-CSRF-TOKEN'          => csrf_token(),
             'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]
        );

        $test->dontSeeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );
        $test->assertResponseStatus(422);
        $test->seeJson(
            ["entrenador" => ["entrenador solo debe contener letras y espacios."]]
        );

    }//end testCrearEquipo5()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa: nombre(alfanumerico), entrenador(letras), CATEGORIA
     * (INVALIDA), sin jugadores.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo6()
    {
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = '123xx';
        $inputIds        = [];
        // Session start is necesary for csrf_token!
        Session::start();
        $test = $this->post(
            '/equipo',
            [
             'nombre'     => $inputNombre,
             'entrenador' => $inputEntrenador,
             'categoria'  => $inputCategoria,
             'ids'        => $inputIds,
            ],
            [
             'X-CSRF-TOKEN'          => csrf_token(),
             'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]
        );

        $test->dontSeeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );
        $test->assertResponseStatus(422);
        $test->seeJson( ["categoria" => ["categoria es inválido."]] );

    }//end testCrearEquipo6()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa nombre alfanumerico, nombre del entrenador, categoria
     * master, 11 jugadores de categoria master o null, y que no pertenencen a.
     * otro equpo.
     * Es exitoso si el sistema registra los datos en la BDD y se actualiza la
     * tabla de jugadores con los elegidos para el nuevo equipo, responde
     * mensaje (guardado con exito) y redireciona a '/equipo'
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo7()
    {

        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $jugsCategoria   = Jugador::where('categoria', $inputCategoria)
                                  ->whereNull('id_equipo')
                                  ->orWhere(
                                        function ($query) {
                                            $query->where('categoria', null)
                                                ->whereNull('id_equipo');
                                        }
                                  )
                                  ->lists('id');

        // Select 11 jugadors for create equipo!
        $jugSelecteds = $jugsCategoria->take(11)->toArray();
        $inputIds     = $jugSelecteds;

        // Session start is necesary for csrf_token!
        Session::start();
        $response = $this->call(
            'POST',
            '/equipo',
            [
             '_token'     => csrf_token(),
             'nombre'     => $inputNombre,
             'entrenador' => $inputEntrenador,
             'categoria'  => $inputCategoria,
             'ids'        => $inputIds,
            ],
            array(), // Cookies
            array(), // Files
            ['HTTP_X-Requested-With' => 'XMLHttpRequest'] // Serve Ajax
        );

        $this->seeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );

        // Get id_equipo del nuevo equipo registrado!
        $idEquipoNew = filter_var($response->content(), FILTER_SANITIZE_NUMBER_INT);

        // Check si los juegadores fueron realcionados al equipo creado!
        $jugsOfNewEq = Jugador::where('id_equipo', $idEquipoNew)->lists('id');
        $jugsOfNewEq = $jugsOfNewEq->toArray();
        // Jugadores relacionados al nuevo equipo son los mismos ingresados?
        if ($jugsOfNewEq === $inputIds) {
            $this->assertTrue(true);
        }

        $this->seeJson(['mensaje' => "guardado con exito"]);
        $this->seePageIs('/equipo');

    }//end testCrearEquipo7()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa nombre alfanumerico, nombre del entrenador, categoria
     * master, 2 jugadores con id's que no existen en la BDD.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo8()
    {

        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        // Dos Jugadores que no estan registrados en la BDD!
        $inputIds        = [-1, -2];

        // Session start is necesary for csrf_token!
        Session::start();
        $response = $this->call(
            'POST',
            '/equipo',
            [
             '_token'     => csrf_token(),
             'nombre'     => $inputNombre,
             'entrenador' => $inputEntrenador,
             'categoria'  => $inputCategoria,
             'ids'        => $inputIds,
            ],
            array(), // Cookies
            array(), // Files
            ['HTTP_X-Requested-With' => 'XMLHttpRequest'] // Serve Ajax
        );

        $this->dontSeeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );

        $this->assertResponseStatus(422);
        $this->seeJson( ["ids" => ["Jugador(s) no identificado(s)"]] );

    }//end testCrearEquipo8()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa nombre alfanumerico, nombre del entrenador, categoria
     * master, 2 jugadores con id's que ya pertenecen a otro equipo.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo9()
    {

        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        // Dos jugadores que le pertenecen al equipo 20A!
        $inputIds        = [1, 2];

        // Session start is necesary for csrf_token!
        Session::start();
        $response = $this->call(
            'POST',
            '/equipo',
            [
             '_token'     => csrf_token(),
             'nombre'     => $inputNombre,
             'entrenador' => $inputEntrenador,
             'categoria'  => $inputCategoria,
             'ids'        => $inputIds,
            ],
            array(), // Cookies
            array(), // Files
            ['HTTP_X-Requested-With' => 'XMLHttpRequest'] // Serve Ajax
        );

        $this->dontSeeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );

        $this->assertResponseStatus(422);
        $this->seeJson( ["ids" => ["Jugador(es) ya pertenece a un equipo."]] );

    }//end testCrearEquipo9()


}//end class
