<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TorneoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    // public function testExample()
    // {
    //     $this->assertTrue(true);
    // }
    public function testNuevoTorneoClick()
    {
        $this->visit('/torneo')
            ->press('nuevoTorneoButton')
            ->seePageIs('/torneo');
    }
    // public function testCrearTorneo()
    // {
        
    // }
}
