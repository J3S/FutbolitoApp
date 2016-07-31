<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Categoria;
use App\Equipo;
use App\Torneo;
use App\Jugador;
use App\Partido;
use App\TorneoEquipo;
use Carbon\Carbon;

class EditarJugadorTest extends TestCase
{
	use DatabaseTransactions;

    /**
     * Comprueba el funcionamiento para editar un jugador.
     * Se ingresan los datos requeridos del jugador.
     * Se prueba el ingreso correcto de solo letras en el nombre del jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 1.
     *
     * @return void
     */
    public function testEditarJugador1()
    {      
        $nombres = "ADRIANO JOHNNY";
        $datonuevo = "PEDRO ADRIANO";
        $apellidos = "OYOLA SUAREZ";
        $registrosEliminados = Jugador::where('nombres', $nombres)
                                      ->where('apellidos', $apellidos)
                                      ->delete();
        Session::start();
        $this->visit(route('jugador.create'))
            ->type($nombres, 'nombres')
            ->type($apellidos, 'apellidos')
            ->press('Guardar');
        $jugadorCreado = Jugador::where('nombres', $nombres)
                                ->where('apellidos', $apellidos)
                                ->first();
        
        $parametros2 = [
            '_method'          => 'PUT',
            '_token'           => csrf_token(),
            'nombres'          => $datonuevo,
        ];

        $uri = "/jugador/".$jugadorCreado->id;
        $response = $this->call('POST', $uri, $parametros2);
        dd($response);
        $this->seeInDatabase(
                'jugadors',
                [
                 'nombres' => $datonuevo,
                 'apellidos' => $apellidos,
                ]
            );
    }

   
}
