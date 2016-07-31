<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Equipo;
class DesactivarEquipoTest extends TestCase
{


    use DatabaseTransactions;


    protected function createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds)
    {
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
    }// end createEquipo()


    /**
     * Comprueba el funcionamiento para desactivar un Equipo.
     * Se crea un equipo, se desactiva
     * se desactiva: un equipo creado
     * Es exitoso si el sistema actualiza el estado del equipo en la BDD.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testDesactivarEquipo1()
    {

        //Crear equipo!
        $inputNombre     = 'Bayern';
        $inputEntrenador = 'Bk';
        $inputCategoria  = 'Master';
        $inputIds        = [];
        $idEquipoCreated = $this->createEquipo($inputNombre, $inputEntrenador, $inputCategoria, $inputIds);

        // Delete equipo!
        $test = $this->delete(
            'equipo/'.$idEquipoCreated,
            array(),
            ['X-CSRF-TOKEN' => csrf_token()]
        );

        $test->seeInDatabase(
            'equipos',
            [
             'id'               => $idEquipoCreated,
             'nombre'           => $inputNombre,
             'director_tecnico' => $inputEntrenador,
             'categoria'        => $inputCategoria,
             'estado'           => false,
            ]
        );

    }//end testDesactivarEquipo1()


    /**
     * Comprueba el funcionamiento para desactivar un Equipo.
     * Se crea un equipo, se desactiva
     * se desactiva: un id que no existe
     * Es exitoso si el sistema NO actualiza el estado del equipo en la BDD.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testDesactivarEquipo2()
    {

        $idEquipoCreated = -1;

        // Delete equipo!
        // Session start is necesary for csrf_token!
        Session::start();
        $this->delete(
            'equipo/'.$idEquipoCreated,
            array(),
            ['X-CSRF-TOKEN' => csrf_token()]
        );

        $this->assertRedirectedTo(route('equipo.index'));

    }//end testDesactivarEquipo2()


}
