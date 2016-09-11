<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Categoria;
use App\Equipo;
use App\Torneo;
use App\Usuario;
use App\TorneoEquipo;
use Carbon\Carbon;

class DesactivarTorneoTest extends TestCase
{
    use DatabaseTransactions;


    /**
     * Comprueba el funcionamiento para editar un torneo.
     * Se crea un torneo que tenga como año 1981, categoría Junior y con un equipo
     * de esa categoría. Se busca a ese torneo recién creado en la página principal
     * de torneo, una vez encontrado se le da click al botón desactivar.
     * Es exitoso si en la base de datos se encuentra el torneo con el estado
     * cambiado a 0.
     * Corresponde al caso de prueba testEditarTorneo: post-condition 1.
     *
     * @return void
     */
    public function testDesactivarTorneo1()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        // Borrar registros con ese año si se han hecho pruebas y no se han eliminado esos registros.
        $registrosEliminados = Torneo::where('anio', '1983')->delete();

        // Creación del torneo.
        $equipoJunior = Equipo::where('categoria', 'Junior')->first();
        // Se inicia una sesión para esta prueba.
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1983',
            'categoria' => 'Junior',
            $equipoJunior->nombre => $equipoJunior->nombre,
        ];
        $response = $this->call('POST', 'torneo', $parametros);

        // Búsqueda del torneo recién creado.
        $torneoCreado = Torneo::where('anio', '1983')
                              ->where('id_categoria', 7)
                              ->first();
        $uri = "/torneo/".$torneoCreado->id;
        $response = $this->call('DELETE', $uri, ['_token' => csrf_token()]);

        $this->seeInDatabase(
            'torneos',
            [
             'anio' => $torneoCreado->anio,
             'id_categoria' => $torneoCreado->id_categoria,
             'estado' => 0,
            ]
        );

        $this->assertEquals(302, $response->getStatusCode());
    }


    /**
     * Comprueba el funcionamiento para editar un torneo.
     * Se trata de desactivar a un torneo que no existe(Prueba realizada por medio
     * de la url).
     * Es exitoso si permanece en la misma página principal de torneo.
     * Corresponde al caso de prueba testEditarTorneoURL
     *
     * @return void
     */
    public function testDesactivarTorneo2()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        // Se inicia una sesión para esta prueba.
        Session::start();
        $torneoUltimoRegistro = Torneo::orderBy('id', 'desc')->first();
        $idTorneoNoExistente = $torneoUltimoRegistro->id+1;
        
        $uri = "/torneo/".$idTorneoNoExistente;
        $response = $this->call('DELETE', $uri, ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testDesactivarTorneoConPartido()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        // Borrar registros con ese año si se han hecho pruebas y no se han eliminado esos registros.
        $torneoEquipo = new TorneoEquipo();
        $torneoEquipo->borrarPorAnio(date('Y'));
        $torneo = new Torneo();
        $torneo->borrarPorAnio(date('Y'));
        // Se inicia una sesión para esta prueba
        Session::start();
        $equipoSuperJunior1 = Equipo::where('categoria', 'Super Junior')->first();
        $equipoSuperJunior2 = Equipo::where('categoria', 'Super Junior')->orderBy('id', 'desc')->first();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => date('Y'),
            'categoria' => 'Super Junior',
            $equipoSuperJunior1->nombre => $equipoSuperJunior1->nombre,
            $equipoSuperJunior2->nombre => $equipoSuperJunior2->nombre,
        ];
        $response = $this->call('POST', 'torneo', $parametros);

        $categoria = Categoria::where('nombre', "Super Junior")->first();
        $torneo = Torneo::where('id_categoria', $categoria->id)->where('anio', date('Y'))->first();
        $date = Carbon::create(date('Y'), 1, 3, 12, 0, 0);
        $jornada = 1;
        $lugar = "Cancha #3";
        $observacion = "No hay observaciones.";
        $arbitro = "John Doe";
        $equipoL = $equipoSuperJunior1;
        $equipoV = $equipoSuperJunior2;
        $estado = 1;
        $golLocal = 1;
        $golVisitante = 0;

        $this->visit(route('partido.create'))
            ->select($torneo->id, 'torneo')
            ->type($jornada, 'jornada')
            ->type($arbitro, 'arbitro')
            ->select($date->format('Y-m-d H:i:s'), 'fecha')
            ->type($lugar, 'lugar')
            ->type($observacion, 'observaciones')
            ->select($equipoL->id, 'equipo_local')
            ->select($equipoV->id, 'equipo_visitante')
            ->check('estado')
            ->type($golLocal, 'gol_local')
            ->type($golVisitante, 'gol_visitante')
            ->press('Guardar');


        $response = $this->call('POST', 'torneo', $parametros);

        $torneoCreado = Torneo::where('anio', date('Y'))
                              ->where('id_categoria', $categoria->id)
                              ->first();
        $uri = "/torneo/".$torneoCreado->id;
        $response = $this->call('DELETE', $uri, ['_token' => csrf_token()]);

        $this->assertEquals(302, $response->getStatusCode());
    }
}
