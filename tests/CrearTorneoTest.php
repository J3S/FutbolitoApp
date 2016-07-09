<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Categoria;
use App\Equipo;
use App\Torneo;

class CrearTorneoTest extends TestCase
{
    use DatabaseTransactions;


    /**
     * Comprueba el funcionamiento para crear un torneo.
     * Se visita la página para crear un torneo, se selecciona la categoría Junior,
     * se ingresa el año 1980 y se manda a guardar el torneo.
     * Es exitoso si en la base de datos se encuentra el torneo con esos datos
     * registrado.
     * Corresponde al caso de prueba testCrearTorneo: post-condition 1.
     *
     * @return void
     */
    public function testCrearTorneo1()
    {
        $categoriaJunior = Categoria::where('nombre', 'Junior')->first();

        $this->visit(route('torneo.create'))
            ->type('1980', 'anio')
            ->select('Junior', 'categoria')
            ->press('Guardar')
            ->seeInDatabase(
                'torneos',
                [
                 'anio' => '1980',
                 'id_categoria' => $categoriaJunior->id,
                ]
            );
    }

    /**
     * Comprueba el funcionamiento para crear un torneo.
     * Se visita la página para crear un torneo, se selecciona la categoría Super
     * Junior, se ingresa el año 1980, se agrega a uno de los equipos que están 
     * disponibles con esa categoría y se manda a guardar el torneo.
     * Es exitoso si en la base de datos se encuentra el torneo con esos datos
     * registrado.
     * Corresponde al caso de prueba testCrearTorneo: post-condition 2.
     *
     * @return void
     */
    public function testCrearTorneo2()
    {
        $equipoSuperJunior = Equipo::where('categoria', 'Super Junior')->first();
        $categoriaSuperJunior = Categoria::where('nombre', 'Super Junior')->first();

        // Se inicia una sesión para esta prueba
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1980',
            'categoria' => 'Super Junior',
            $equipoSuperJunior->nombre => $equipoSuperJunior->nombre,
        ];
        $response = $this->call('POST', 'torneo', $parametros);

        $this->seeInDatabase(
            'torneos',
            [
             'anio' => '1980',
             'id_categoria' => $categoriaSuperJunior->id,
            ]
        );
    }

    /**
     * Comprueba el funcionamiento para crear un torneo.
     * Se visita la página para crear un torneo, se selecciona la categoría Super
     * Master, se ingresa el año abcd y se manda a guardar el torneo.
     * Es exitoso si se mantiene en la misma página torneo/create.
     * Corresponde al caso de prueba testCrearTorneo: post-condition 3.
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
     * Comprueba el funcionamiento para crear un torneo.
     * Se visita la página para crear un torneo, se selecciona la categoría Rey
     * Master, se ingresa el año 1980, se agrega a un equipo que no está registrado
     * en la base y se manda a guardar el torneo.
     * Es exitoso si se mantiene en la misma página torneo/create.
     * Corresponde al caso de prueba testCrearTorneo: post-condition 4.
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
            'Equipo no registrado' => 'Equipo no registrado',
        ];

        $response = $this->call('POST', 'torneo', $parametros);

        $this->assertRedirectedToRoute('torneo.create');
    }

    /**
     * Comprueba el funcionamiento para crear un torneo.
     * Se visita la página para crear un torneo, se selecciona la categoría Senior,
     * se ingresa el año 1980, se agrega a uno de los equipos que están disponibles
     * con esa categoría, se selecciona la categoría Super Senior, se agrega a uno
     * de los equipos que están disponibles con esa categoría y se manda a guardar
     * el torneo.
     * Es exitoso si se mantiene en la misma página torneo/create.
     * Corresponde al caso de prueba testCrearTorneo: post-condition 5.
     *
     * @return void
     */
    public function testCrearTorneo5()
    {
        $equipoSenior = Equipo::where('categoria', 'Senior')->first();
        $equipoSuperSenior = Equipo::where('categoria', 'Super Senior')->first();
        // Se inicia una sesión para esta prueba
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1980',
            'categoria' => 'Super Senior',
            $equipoSenior->nombre => $equipoSenior->nombre,
            $equipoSuperSenior->nombre => $equipoSuperSenior->nombre,
        ];

        $response = $this->call('POST', 'torneo', $parametros);

        $this->assertRedirectedToRoute('torneo.create');
    }

    /**
     * Comprueba el funcionamiento para crear un torneo.
     * Se visita la página para crear un torneo, se agrega una categoría que no está
     * registrada en la base, se ingresa el año 1980 y se manda a guardar el torneo.
     * Es exitoso si se mantiene en la misma página torneo/create.
     * Corresponde al caso de prueba testCrearTorneo: post-condition 6.
     *
     * @return void
     */
    public function testCrearTorneo6()
    {
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1980',
            'categoria' => 'Categoria no registrada',
        ];

        $response = $this->call('POST', 'torneo', $parametros);

        $this->assertRedirectedToRoute('torneo.create');
    }

    /**
     * Comprueba el funcionamiento para crear un torneo.
     * Se visita la página para crear un torneo, se agrega una categoría master,
     * se ingresa el año 1969(menor al año mínimo) y se manda a guardar el torneo.
     * Es exitoso si se mantiene en la misma página torneo/create.
     * Corresponde al caso de prueba testCrearTorneo: post-condition 7.
     *
     * @return void
     */
    public function testCrearTorneo7()
    {
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1969',
            'categoria' => 'Master',
        ];

        $response = $this->call('POST', 'torneo', $parametros);

        $this->assertRedirectedToRoute('torneo.create');
    }

    /**
     * Comprueba el funcionamiento para crear un torneo.
     * Se visita la página para crear un torneo, se agrega una categoría master,
     * se ingresa el año 10000 y se manda a guardar el torneo.
     * Es exitoso si se mantiene en la misma página torneo/create.
     * Corresponde al caso de prueba testCrearTorneo: post-condition 8.
     *
     * @return void
     */
    public function testCrearTorneo8()
    {
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '10000',
            'categoria' => 'Master',
        ];

        $response = $this->call('POST', 'torneo', $parametros);

        $this->assertRedirectedToRoute('torneo.create');
    }

}
