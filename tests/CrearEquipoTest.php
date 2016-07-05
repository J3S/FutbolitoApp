<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CrearEquipoTest extends TestCase
{
    // use DatabaseTransactions;

    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en formato JSON,
     * se ingresa nombre alfanumerico, se omite entrenador, categoria master,
     * sin jugadores.
     * Es exitoso si el sistema responde con un mensaje 'guadrado con exito'.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo1()
    {
        $this->post(
                    '/equipo',
                    [
                        'nombre' => 'MiEquipo',
                        'entrenador' => '',
                        'categoria' => 'Master',
                        'ids' => null,
                    ]
                    )
             ->seeJson([
                 'mensaje' => 'guardado con exito',
             ]);
    }

    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en formato JSON,
     * se ingresa nombre alfanumerico(MiEquipo), entrenador solo letras(Jorge),
     * categoria(Master), sin jugadores.
     * Es exitoso si el sistema responde con un mensaje 'guadrado con exito'.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo2()
    {
        $this->post(
                    '/equipo',
                    [
                        'nombre' => 'MiEquipo',
                        'entrenador' => 'Jorge',
                        'categoria' => 'Master',
                        'ids' => null,
                    ]
                    )
             ->seeJson([
                 'mensaje' => 'guardado con exito',
             ]);
    }

    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en formato JSON,
     * se omite nombre del equipo, entrenador solo letras(Jorge),
     * categoria(Master), sin jugadores.
     * Es exitoso si el sistema responde con un estado 422 de las validaciones.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo3()
    {
        $response = $this->call(
                    'POST',
                    'equipo',
                    [
                        'nombre' => null,
                        'entrenador' => 'Jorge',
                        'categoria' => 'Master',
                        'ids' => null,
                    ]
                    );

        $this->assertEquals(422, $response->status());

    }

    /**
     * Comprueba el funcionamiento para crear un Equipo.
     * Se crea un equipo y para guardar se envia por ajax los datos en formato JSON,
     * se ingresa nombre alfanumerico(MiEquipo), entrenador numeros(1234),
     * categoria(Master), sin jugadores.
     * Es exitoso si el sistema responde con un estado 422 de las validaciones.
     * Corresponde al caso de prueba testCrearEquipo: post-condition 1.
     *
     * @return void
     */
    public function testCrearEquipo4()
    {
        $response = $this->call(
                    'POST',
                    'equipo',
                    [
                        'nombre' => 'MiEquipo',
                        'entrenador' => '1234',
                        'categoria' => 'Master',
                        'ids' => '',
                    ]
                    );

        $this->assertEquals(422, $response->status());

    }
}
