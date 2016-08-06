<?php
/**
 * This file contains common functions used for testing when user creates Equipo.
 * PHP version 5
 *
 * @category   Testing
 * @package    FutbolitoApp
 * @subpackage Tests
 * @author     Branny Chito <brajchit@espol.edu.ec>
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @link       http://definirlink.local
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Equipo;
use App\Jugador;
use App\Usuario;

/**
 * EditarEquipoTest Class Doc Comment
 *
 * @category PHPunit
 * @package  Test
 * @author   Branny Chito <brajchit@espol.edu.ec>
 * @license  MIT, http://opensource.org/licenses/MIT
 * @link     http://definirlink.local
 */
class EditarEquipoTest extends TestCase
{


    use DatabaseTransactions;


    protected function createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds)
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        // Session start is necesary for csrf_token!
        Session::start();
        $this->post(
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

        // Search created equipo!
        $equipoCreated = Equipo::where('nombre', $inputNombre)->first();

        return $equipoCreated->id;
    }


    /**
     * Comprueba el funcionamiento para editar un Equipo.
     * Se crea un equipo, se edita y para actualizar se envia por ajax los
     * datos en JSON,
     * se edita: nombre(alfanumerico), entrenador(solo letras), categoria
     * master, sin jugadores.
     * Es exitoso si el sistema Actualiza los datos en la BDD, responde mensaje
     * (actualizado con exito) y redireciona a '/equipo'
     * Corresponde al caso de prueba testEditarEquipo: post-condition 1.
     *
     * @return void
     */
    public function testEditarEquipo1()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        //Crear equipo!
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $idEquipoCreated = $this->createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds);

        // Edit *name equipo!
        $inputNombre = 'Bayern Munich';

        // Update equipo!
        $test = $this->put(
            'equipo/'.$idEquipoCreated,
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
        $test->seeJson(['mensaje' => "actualizado con exito"]);
        $test->seePageIs('equipo/'.$idEquipoCreated);

    }//end testEditarEquipo1()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa: nombre(ninguno), entrenador(solo letras), categoria
     * master, sin jugadores.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testEditarEquipo: post-condition 1.
     *
     * @return void
     */
    public function testEditarEquipo2()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        //Crear equipo!
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $idEquipoCreated = $this->createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds);

        // Edit *name equipo!
        $inputNombre = '';

        // Update equipo!
        $test = $this->put(
            'equipo/'.$idEquipoCreated,
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

    }//end testEditarEquipo2()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa: nombre(ninguno), entrenador(solo letras), categoria
     * master, sin jugadores.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testEditarEquipo: post-condition 1.
     *
     * @return void
     */
    public function testEditarEquipo3()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        //Crear equipo!
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $idEquipoCreated = $this->createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds);

        // Edit *name equipo!
        $inputNombre = '20A';

        // Update equipo!
        $test = $this->put(
            'equipo/'.$idEquipoCreated,
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

    }//end testEditarEquipo3()


    /**
     * Comprueba el funcionamiento para editar un Equipo.
     * Se crea un equipo, se edita y para actualizar se envia por ajax los
     * datos en JSON,
     * se edita: nombre(alfanumerico), entrenador(ninguno), categoria
     * master, sin jugadores.
     * Es exitoso si el sistema Actualiza los datos en la BDD, responde mensaje
     * (actualizado con exito) y redireciona a '/equipo'
     * Corresponde al caso de prueba testEditarEquipo: post-condition 1.
     *
     * @return void
     */
    public function testEditarEquipo4()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        //Crear equipo!
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $idEquipoCreated = $this->createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds);

        // Edit *entrenador equipo!
        $inputEntrenador = '';

        // Update equipo!
        $test = $this->put(
            'equipo/'.$idEquipoCreated,
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
        $test->seeJson(['mensaje' => "actualizado con exito"]);
        $test->seePageIs('/equipo/'.$idEquipoCreated);

    }//end testEditarEquipo4()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa: nombre(ninguno), entrenador(alfanumerico), categoria
     * master, sin jugadores.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testEditarEquipo: post-condition 1.
     *
     * @return void
     */
    public function testEditarEquipo5()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        //Crear equipo!
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $idEquipoCreated = $this->createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds);

        // Edit *entrenador equipo!
        $inputEntrenador = 'Geek 1337';

        // Update equipo!
        $test = $this->put(
            'equipo/'.$idEquipoCreated,
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

    }//end testEditarEquipo5()


    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en JSON,
     * se ingresa: nombre(ninguno), entrenador(alfanumerico), categoria
     * INVALIDA, sin jugadores.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testEditarEquipo: post-condition 1.
     *
     * @return void
     */
    public function testEditarEquipo6()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        //Crear equipo!
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $idEquipoCreated = $this->createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds);

        // Edit *categoria equipo!
        $inputCategoria = 'Categoria 1337';

        // Update equipo!
        $test = $this->put(
            'equipo/'.$idEquipoCreated,
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
        $test->seeJson(["categoria" => ["categoria es inválido."]]);

    }//end testEditarEquipo6()


    /**
     * Comprueba el funcionamiento para editar un Equipo.
     * Se crea un equipo, se edita y para actualizar se envia por ajax los
     * datos en JSON,
     * se edita: nombre(alfanumerico), entrenador(solo letras), categoria
     * master, 11 jugadores de categoria master o null, y que no pertenencen a.
     * otro equpo.
     * Es exitoso si el sistema registra los datos en la BDD y se actualiza la
     * tabla de jugadores con los elegidos para el nuevo equipo, responde
     * mensaje (guardado con exito) y redireciona a '/equipo'
     * Corresponde al caso de prueba testEditarEquipo: post-condition 1.
     *
     * @return void
     */
    public function testEditarEquipo7()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        //Crear equipo!
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $idEquipoCreated = $this->createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds);

        // Select 11 jugadors for update empty jugs equipo!
        $jugsCategoria   = Jugador::where('categoria', $inputCategoria)
                                  ->whereNull('id_equipo')
                                  ->orWhere(
                                        function ($query) {
                                            $query->where('categoria', null)
                                                ->whereNull('id_equipo');
                                        }
                                  )
                                  ->lists('id');

        $jugSelecteds = $jugsCategoria->take(11)->toArray();
        // Edit *jugadores equipo!
        $inputIds     = $jugSelecteds;

        // Update equipo!
        $this->call(
            'PUT',
            '/equipo/'.$idEquipoCreated,
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

        // Check si los juegadores fueron realcionados al equipo creado!
        $jugsOfNewEq = Jugador::where('id_equipo', $idEquipoCreated)->lists('id');
        $jugsOfNewEq = $jugsOfNewEq->toArray();
        // Jugadores relacionados al nuevo equipo son los mismos actualizados?
        $this->assertTrue($jugsOfNewEq === $inputIds);

        $this->seeJson(['mensaje' => "actualizado con exito"]);
        $this->seePageIs('/equipo/'.$idEquipoCreated);

    }//end testEditarEquipo7()


    /**
     * Comprueba el funcionamiento para editar un Equipo.
     * Se crea un equipo, se edita y para actualizar se envia por ajax los
     * datos en JSON,
     * se edita: nombre(alfanumerico), entrenador(solo letras), categoria
     * master, 2 jugadores con id's que no existen en la BDD.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testEditarEquipo: post-condition 1.
     *
     * @return void
     */
    public function testEditarEquipo8()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        //Crear equipo!
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $idEquipoCreated = $this->createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds);

        // Edit *jugadores equipo!
        $inputIds = [-1, -2];

        // Update equipo!
        $this->call(
            'PUT',
            '/equipo/'.$idEquipoCreated,
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

        // Aunque no se actualizo los jugadores los otros datos si estan!
        $this->SeeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );

        $this->assertResponseStatus(422);
        $this->seeJson(["ids" => ["Jugador(s) no identificado(s)"]]);

    }//end testEditarEquipo8()


    /**
     * Comprueba el funcionamiento para editar un Equipo.
     * Se crea un equipo, se edita y para actualizar se envia por ajax los
     * datos en JSON,
     * se edita: nombre(alfanumerico), entrenador(solo letras), categoria
     * master, 2 jugadores con id's que ya pertenecen a otro equipo.
     * Es exitoso si el sistema NO registra los datos en la BDD, responde
     * con estado 422 y un mensaje de error de validación.
     * Corresponde al caso de prueba testEditarEquipo: post-condition 1.
     *
     * @return void
     */
    public function testEditarEquipo9()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        //Crear equipo!
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $idEquipoCreated = $this->createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds);

        // Edit 2 *jugadores que le pertenecen al equipo 20A!
        $inputIds        = [1, 2];

        // Update equipo!
        $this->call(
            'PUT',
            '/equipo/'.$idEquipoCreated,
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

        // Aunque no se actualizo los jugadores los otros datos si estan!
        $this->SeeInDatabase(
            'equipos',
            [
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
            ]
        );

        $this->assertResponseStatus(422);
        $this->seeJson(["ids" => ["Jugador(es) ya pertenece a un equipo."]]);

    }//end testEditarEquipo9()


    /**
     * Comprueba el funcionamiento para editar un Equipo.
     * Se crea un equipo, se edita y para actualizar se envia por ajax los
     * datos en JSON,
     * se edita un equipo con id_equipo que no esta activo o no existe.
     * Es exitoso si el sistema NO muestra el formulario para editar, responde
     * con un mensaje que el equipo no existe.
     * Corresponde al caso de prueba testEditarEquipo: post-condition 1.
     *
     * @return void
     */
    public function testEditarEquipo10()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $idEquipo = -1;
        $this->get('/equipo/'.$idEquipo.'/edit');
        $this->assertRedirectedTo('/equipo');

    }//end testEditarEquipo10()


}//end class
