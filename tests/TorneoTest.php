<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TorneoTest extends TestCase
{
    use DatabaseTransactions;


    /**
     * Comprueba el funcionamiento del botón para crear un nuevo torneo
     * Es exitoso si la página que se obtienen tiene la ruta /torneo/create
     *
     * @return void
     */
    // public function testNuevoTorneoClick()
    // {
    //     $this->visit(route('torneo.index'))
    //          ->click('Crear Torneo')
    //          ->seePageIs(route('torneo.create'));
    // }

    /**
     * Comprueba el funcionamiento para crear un torneo
     * Es exitoso si la página que se obtienen tiene la ruta /torneo
     * Corresponde al caso de prueba testCrearTorneo: post-condition 1
     *
     * @return void
     */
    public function testCrearTorneo1()
    {
        $this->visit(route('torneo.create'))
             ->type('1980', 'anio')
             ->select('Junior', 'categoria')
             ->press('Guardar')
             ->seePageIs(route('torneo.index'));
    }

    /**
     * Comprueba el funcionamiento para crear un torneo
     * Es exitoso si la página que se obtienen tiene la ruta /torneo
     * Corresponde al caso de prueba testCrearTorneo: post-condition 2
     *
     * @return void
     */
    public function testCrearTorneo2()
    {
        // Se inicia una sesión para esta prueba
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1980',
            'categoria' => 'Super Junior',
            '23C' => '23C',
        ];
        $response = $this->call('POST', 'torneo', $parametros);

        $this->assertRedirectedToRoute('torneo.index');
    }

    /**
     * Comprueba el funcionamiento para crear un torneo
     * Es exitoso si la página que se obtienen tiene la ruta /torneo/create
     * Corresponde al caso de prueba testCrearTorneo: post-condition 3
     *
     * @return void
     */
    public function testCrearTorneo3()
    {
        $this->visit(route('torneo.create'))
             ->type('abcd', 'anio')
             ->select('Super Master', 'categoria')
             ->press('Guardar')
             ->seePageIs(route('torneo.create'));
    }

    /**
     * Comprueba el funcionamiento para crear un torneo
     * Es exitoso si la página que se obtienen tiene la ruta /torneo
     * Corresponde al caso de prueba testCrearTorneo: post-condition 4
     *
     * @return void
     */
    public function testCrearTorneo4()
    {
        // Se inicia una sesión para esta prueba
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1980',
            'categoria' => 'Rey Master',
            '23A' => '23A',
        ];

        $response = $this->call('POST', 'torneo', $parametros);

        $this->assertRedirectedToRoute('torneo.index');
    }

    /**
     * Comprueba el funcionamiento para crear un torneo
     * Es exitoso si la página que se obtienen tiene la ruta /torneo/create
     * Corresponde al caso de prueba testCrearTorneo: post-condition 5
     *
     * @return void
     */
    public function testCrearTorneo5()
    {
        // Se inicia una sesión para esta prueba
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1980',
            'categoria' => 'Super Senior',
            '20A' => '20A',
            '23D' => '23D',
        ];

        $response = $this->call('POST', 'torneo', $parametros);

        $this->assertRedirectedToRoute('torneo.create');
    }

}
