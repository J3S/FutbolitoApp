<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResourceTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetCategorias(){
        $response = $this->call('GET', '/get_categorias');
        $this->assertResponseStatus(200);
    }

    public function testGetCategoria(){
        $response = $this->call('GET', '/get_categoria/1');
        $this->assertResponseStatus(200);
    }

    public function testGetJugadores(){
        $response = $this->call('GET', '/get_jugadores');
        $this->assertResponseStatus(200);
    }

	public function testGetJugador(){
        $response = $this->call('GET', '/get_jugador/1');
        $this->assertResponseStatus(200);
    }
    
    public function testGetEquipos(){
        $response = $this->call('GET', '/get_equipos');
        $this->assertResponseStatus(200);
    }

	public function testGetEquipo(){
        $response = $this->call('GET', '/get_equipo/1');
        $this->assertResponseStatus(200);
    }

    public function testGetPartidos(){
        $response = $this->call('GET', '/get_partidos');
        $this->assertResponseStatus(200);
    }

    public function testGetPartido(){
        $response = $this->call('GET', '/get_partido/1');
        $this->assertResponseStatus(200);
    }

    public function testGetTorneos(){
        $response = $this->call('GET', '/get_torneos');
        $this->assertResponseStatus(200);
    }

    public function testGetTorneo(){
        $response = $this->call('GET', '/get_torneo/1');
        $this->assertResponseStatus(200);
    }

    public function testGetTorneoEquipos(){
        $response = $this->call('GET', '/get_torneoequipos');
        $this->assertResponseStatus(200);
    }

    public function testGetTorneoEquipo(){
        $response = $this->call('GET', '/get_torneoequipo/1');
        $this->assertResponseStatus(200);
    }

    public function testGetJornada(){
    	$response = $this->call('GET', '/get_jornada/1/1');
        $this->assertResponseStatus(200);
    }

    public function testGetAnioTorneos(){
    	$response = $this->call('GET', '/get_aniotorneos/2016');
        $this->assertResponseStatus(200);
    }

	public function testGetAniosConTorneos(){
    	$response = $this->call('GET', '/get_anioscontorneos');
        $this->assertResponseStatus(200);
    }

    public function testGetTablaPosiciones1(){
        $response = $this->call('GET', '/get_tablaposiciones/1');
        $this->assertResponseStatus(200);
    }

    public function testGetTablaPosiciones2(){
        $response = $this->call('GET', '/get_tablaposiciones/2');
        $this->assertResponseStatus(200);
    }

    public function testGetTablaPosiciones3(){
        $response = $this->call('GET', '/get_tablaposiciones/3');
        $this->assertResponseStatus(200);
    }
}
