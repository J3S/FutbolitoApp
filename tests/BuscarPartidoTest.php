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

class BuscarPartidoTest extends TestCase
{

	use DatabaseTransactions;

    /**
     * Comprueba el funcionamiento de buscar un partido.
     *
     * @return void
     */
    public function testBuscarPartido()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $anio = 2016;
        $jornada = 1;
        $ini = Carbon::create(2016, 1, 1, 12, 0, 0);
        $fin = Carbon::create(2016, 10, 10, 12, 0, 0);
        $categoria = Categoria::where('nombre', "Rey Master")->first();
        $equipos = Equipo::where('estado', 1)->where('categoria', $categoria->nombre)->get();
        $equipoL = $equipos[0];
        $equipoV = $equipos[1];

        $this->visit(route('partido.index'))
            ->type($anio, 'anio')
            ->select($categoria->nombre, 'categoria')
            ->select($ini->format('Y-m-d H:i:s'), 'ini_partido')
            ->select($fin->format('Y-m-d H:i:s'), 'fin_partido')
            ->select($equipoL->id, 'equipo_local')
            ->select($equipoV->id, 'equipo_visitante')
            ->type($jornada, 'jornada')
            ->press('btnFormBuscarPartido')
            ->seePageIs('/selectPartido');
    }
}

