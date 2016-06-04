<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TorneoTest extends TestCase
{
    /**
     * Comprueba el funcionamiento del botón para crear un nuevo torneo
     * Es exitoso si la página que se obtienen tiene la ruta /torneo/create
     *
     * @return void
     */
    public function testNuevoTorneoClick()
    {
        $this->visit(route('torneo.index'))
             ->click('Crear Torneo')
             ->seePageIs(route('torneo.create'));
    }
    // public function testCrearTorneo()
    // {
        
    // }
}
