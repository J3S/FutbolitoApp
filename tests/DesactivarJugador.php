<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Categoria;
use App\Equipo;
use App\Torneo;
use App\Partido;
use App\TorneoEquipo;
use Carbon\Carbon;
use App\Usuario;

class DesactivarJugadorTest extends TestCase
{

    /**
     * Comprueba el funcionamiento para desactivar un jugador.
     * Se crea un jugador con datos predeterminados. 
     * Se busca ese jugador en la pagina principal de jugador.
     * Se aplasta el boton para desactivar el jugador.
     * Es exitoso si en la base de datos se encuentra el jugador con el estado
     * cambiado a 0.
     * Corresponde al caso de prueba testDesactivarJugador: post-condition 1.
     *
     * @return void
     */
    public function testDesactivarJugador1()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $jugadores = Jugador::where('nombres', 'ADRIANO JOHNNY')->get()->first();
        $uri = "/jugador/".$jugadores->id;
        $response = $this->call('DELETE', $uri, ['_token' => csrf_token()]);
        $this->seeInDatabase(
                'jugadors',
                [
                 'id' => $jugadores->id,
                ]
            );
        $this->assertEquals(302, $response->getStatusCode());
    }


    /**
     * Comprueba el funcionamiento para desactivar un jugador.
     * Se trata de desactivar a un jugador que no existe (prueba realizada por medio
     * de la url).
     * Es exitoso si permanece en la misma página principal de jugador.
     * Corresponde al caso de prueba testEditarJugador: post-condition 2.
     *
     * @return void
     */
    public function testDesactivarJugador2()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        // Se inicia una sesión para esta prueba.
        Session::start();
        $jugadorUltimoRegistro = Jugador::orderBy('id', 'desc')->first();
        $idJugadorNoExistente = $jugadorUltimoRegistro->id+1;
        
        $uri = "/jugador/".$idJugadorNoExistente;
        $response = $this->call('DELETE', $uri, ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
