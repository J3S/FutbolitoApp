<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Categoria;
use App\Equipo;
use App\Torneo;

class EquipoTest extends TestCase
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
    public function testCrearEquipo1()
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
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
