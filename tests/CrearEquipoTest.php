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
             'ids'        => [],
            ],
            ['X-CSRF-TOKEN' => csrf_token()]
        );
        // $this->dump(); // testing debug
        // $this->expectOutputString("cont: ".$response->content()); //testing debug
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
             'ids'        => [],
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
             'ids'        => [],
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
             'ids'        => [],
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
             'ids'        => [],
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
             'ids'        => [],
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
            ["categoria" => ["categoria es inválido."]]
        );

    }//end testCrearEquipo6()


}//end class
