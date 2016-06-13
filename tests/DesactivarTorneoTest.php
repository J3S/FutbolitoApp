<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Categoria;
use App\Equipo;
use App\Torneo;

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
        // Se inicia una sesión para esta prueba.
        Session::start();
        $torneoUltimoRegistro = Torneo::orderBy('id', 'desc')->first();
        $idTorneoNoExistente = $torneoUltimoRegistro->id+1;
        
        $uri = "/torneo/".$idTorneoNoExistente;
        $response = $this->call('DELETE', $uri, ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
