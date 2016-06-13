<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Categoria;
use App\Equipo;
use App\Torneo;

class EditarTorneoTest extends TestCase
{
    use DatabaseTransactions;


    /**
     * Comprueba el funcionamiento para editar un torneo.
     * Se crea un torneo que tenga como año 1981, categoría Junior y con un equipo
     * de esa categoría. Se edita ese torneo recién creado modificándole la
     * categoría por Super Junior, quitándole el equipo agregado y se le agrega un
     * equipo de esa categoría y se manda a actualizar.
     * Es exitoso si en la base de datos se encuentra el torneo con esos datos
     * modificados y se redirige a la vista principal de torneo.
     * Corresponde al caso de prueba testEditarTorneo: post-condition 1.
     *
     * @return void
     */
    public function testEditarTorneo1()
    {
        // Borrar registros con ese año si se han hecho pruebas y no se han eliminado esos registros.
        $registrosEliminados = Torneo::where('anio', '1981')->delete();

        // Creación del torneo.
        $equipoJunior = Equipo::where('categoria', 'Junior')->first();
        // Se inicia una sesión para esta prueba.
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1981',
            'categoria' => 'Junior',
            $equipoJunior->nombre => $equipoJunior->nombre,
        ];
        $response = $this->call('POST', 'torneo', $parametros);

        // Búsqueda del torneo recién creado.
        $torneoCreado = Torneo::where('anio', '1981')
                              ->where('id_categoria', 7)
                              ->first();
        // Modificación del torneo.
        $equipoSuperJunior = Equipo::where('categoria', 'Super Junior')->first();
        $categoriaSuperJunior = Categoria::where('nombre', 'Super Junior')->first();
        $parametros = [
            '_method' => 'PUT',
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1981',
            'categoria' => 'Super Junior',
            $equipoSuperJunior->nombre => $equipoSuperJunior->nombre,
        ];
        $uri = "/torneo/".$torneoCreado->id;
        $response = $this->call('POST', $uri, $parametros);

        $this->seeInDatabase(
            'torneos',
            [
             'anio' => '1981',
             'id_categoria' => $categoriaSuperJunior->id,
            ]
        );

        $this->assertRedirectedToRoute('torneo.index');
    }


    /**
     * Comprueba el funcionamiento para editar un torneo.
     * Se crea un torneo que tenga como año 1981, categoría Junior y con un equipo
     * de esa categoría. Se edita ese torneo recién creado modificándole el año por
     * 1982 y se manda a actualizar.
     * Es exitoso si en la base de datos se encuentra el torneo con esos datos
     * modificados y se redirige a la vista principal de torneo.
     * Corresponde al caso de prueba testEditarTorneo: post-condition 2.
     *
     * @return void
     */
    public function testEditarTorneo2()
    {
        // Borrar registros con ese año si se han hecho pruebas y no se han eliminado esos registros.
        $registrosEliminados = Torneo::where('anio', '1981')->delete();

        // Creación del torneo.
        $equipoJunior = Equipo::where('categoria', 'Junior')->first();
        // Se inicia una sesión para esta prueba.
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1981',
            'categoria' => 'Junior',
            $equipoJunior->nombre => $equipoJunior->nombre,
        ];
        $response = $this->call('POST', 'torneo', $parametros);

        //Búsqueda del torneo recién creado.
        $torneoCreado = Torneo::where('anio', '1981')
                              ->where('id_categoria', 7)
                              ->first();
        // Modificación del torneo.
        $categoriaJunior = Categoria::where('nombre', 'Junior')->first();
        $parametros = [
            '_method' => 'PUT',
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1982',
            'categoria' => 'Junior',
            $equipoJunior->nombre => $equipoJunior->nombre,
        ];
        $uri = "/torneo/".$torneoCreado->id;
        $response = $this->call('POST', $uri, $parametros);

        $this->seeInDatabase(
            'torneos',
            [
             'anio' => '1982',
             'id_categoria' => $categoriaJunior->id,
            ]
        );

        $this->assertRedirectedToRoute('torneo.index');
    }


    /**
     * Comprueba el funcionamiento para editar un torneo.
     * Se crea un torneo que tenga como año 1981, categoría Junior y con un equipo
     * de esa categoría. Se edita ese torneo recién creado modificándole el año por
     * abcd y se manda a actualizar.
     * Es exitoso si en la base de datos se encuentra que el torneo queda con los
     * mismos datos y permanece en la misma página para editar ese torneo.
     * Corresponde al caso de prueba testEditarTorneo: post-condition 3.
     *
     * @return void
     */
    public function testEditarTorneo3()
    {
        // Borrar registros con ese año si se han hecho pruebas y no se han eliminado esos registros.
        $registrosEliminados = Torneo::where('anio', '1981')->delete();

        // Creación del torneo.
        $equipoJunior = Equipo::where('categoria', 'Junior')->first();
        // Se inicia una sesión para esta prueba.
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1981',
            'categoria' => 'Junior',
            $equipoJunior->nombre => $equipoJunior->nombre,
        ];
        $response = $this->call('POST', 'torneo', $parametros);

        //Búsqueda del torneo recién creado.
        $torneoCreado = Torneo::where('anio', '1981')
                              ->where('id_categoria', 7)
                              ->first();
        // Modificación del torneo.
        $categoriaJunior = Categoria::where('nombre', 'Junior')->first();
        $parametros = [
            '_method' => 'PUT',
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => 'abcd',
            'categoria' => 'Junior',
            $equipoJunior->nombre => $equipoJunior->nombre,
        ];
        $uri = "/torneo/".$torneoCreado->id;
        $response = $this->call('POST', $uri, $parametros);
        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
            'torneos',
            [
             'anio' => '1981',
             'id_categoria' => $categoriaJunior->id,
            ]
        );
    }


    /**
     * Comprueba el funcionamiento para editar un torneo.
     * Se crea un torneo que tenga como año 1981, categoría Junior y con un equipo
     * de esa categoría. Se edita ese torneo recién creado quitándole el equipo
     * agregado y agregándole un equipo que no está registrado.
     * Es exitoso si en la base de datos se encuentra que el torneo queda con los
     * mismos datos y permanece en la misma página para editar ese torneo.
     * Corresponde al caso de prueba testEditarTorneo: post-condition 4.
     *
     * @return void
     */
    public function testEditarTorneo4()
    {
        // Borrar registros con ese año si se han hecho pruebas y no se han eliminado esos registros.
        $registrosEliminados = Torneo::where('anio', '1981')->delete();

        // Creación del torneo.
        $equipoJunior = Equipo::where('categoria', 'Junior')->first();
        // Se inicia una sesión para esta prueba.
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1981',
            'categoria' => 'Junior',
            $equipoJunior->nombre => $equipoJunior->nombre,
        ];
        $response = $this->call('POST', 'torneo', $parametros);

        //Búsqueda del torneo recién creado.
        $torneoCreado = Torneo::where('anio', '1981')
                              ->where('id_categoria', 7)
                              ->first();
        // Modificación del torneo.
        $parametros = [
            '_method' => 'PUT',
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1981',
            'categoria' => 'Junior',
            'Equipo Test' => 'Equipo Test',
        ];
        $uri = "/torneo/".$torneoCreado->id;
        $response = $this->call('POST', $uri, $parametros);
        $this->assertEquals(302, $response->getStatusCode());
    }


    /**
     * Comprueba el funcionamiento para editar un torneo.
     * Se crea un torneo que tenga como año 1981, categoría Junior y con un equipo
     * de esa categoría. Se edita ese torneo recién creado modificándole la
     * categoría, agregándole un equipo de esa categoría y se manda a actualizar.
     * Es exitoso si en la base de datos se encuentra que el torneo queda con los
     * mismos datos y permanece en la misma página para editar ese torneo.
     * Corresponde al caso de prueba testEditarTorneo: post-condition 5.
     *
     * @return void
     */
    public function testEditarTorneo5()
    {
        // Borrar registros con ese año si se han hecho pruebas y no se han eliminado esos registros.
        $registrosEliminados = Torneo::where('anio', '1981')->delete();

        // Creación del torneo.
        $equipoJunior = Equipo::where('categoria', 'Junior')->first();
        // Se inicia una sesión para esta prueba.
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1981',
            'categoria' => 'Junior',
            $equipoJunior->nombre => $equipoJunior->nombre,
        ];
        $response = $this->call('POST', 'torneo', $parametros);

        //Búsqueda del torneo recién creado.
        $torneoCreado = Torneo::where('anio', '1981')
                              ->where('id_categoria', 7)
                              ->first();
        // Modificación del torneo.
        $equipoSenior = Equipo::where('categoria', 'Senior')->first();
        $categoriaJunior = Categoria::where('nombre', 'Junior')->first();
        $parametros = [
            '_method' => 'PUT',
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1981',
            'categoria' => 'Senior',
            $equipoJunior->nombre => $equipoJunior->nombre,
            $equipoSenior->nombre => $equipoSenior->nombre,
        ];
        $uri = "/torneo/".$torneoCreado->id;
        $response = $this->call('POST', $uri, $parametros);
        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
            'torneos',
            [
             'anio' => '1981',
             'id_categoria' => $categoriaJunior->id,
            ]
        );
    }

    /**
     * Comprueba el funcionamiento para editar un torneo.
     * Se crea un torneo que tenga como año 1981, categoría Junior y con un equipo
     * de esa categoría. Se edita ese torneo recién creado modificándole la
     * categoría, colocando como categoría "Categoría Test".
     * Es exitoso si en la base de datos se encuentra que el torneo queda con los
     * mismos datos y permanece en la misma página para editar ese torneo.
     * Corresponde al caso de prueba testEditarTorneo: post-condition 6.
     *
     * @return void
     */
    public function testEditarTorneo6()
    {
        // Borrar registros con ese año si se han hecho pruebas y no se han eliminado esos registros.
        $registrosEliminados = Torneo::where('anio', '1981')->delete();

        // Creación del torneo.
        $equipoJunior = Equipo::where('categoria', 'Junior')->first();
        // Se inicia una sesión para esta prueba.
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1981',
            'categoria' => 'Junior',
            $equipoJunior->nombre => $equipoJunior->nombre,
        ];
        $response = $this->call('POST', 'torneo', $parametros);

        //Búsqueda del torneo recién creado.
        $torneoCreado = Torneo::where('anio', '1981')
                              ->where('id_categoria', 7)
                              ->first();
        // Modificación del torneo.
        $categoriaJunior = Categoria::where('nombre', 'Junior')->first();
        $parametros = [
            '_method' => 'PUT',
            '_token' => csrf_token(), // Obteniendo el csrf token
            'anio' => '1981',
            'categoria' => 'Categoria Test',
            $equipoJunior->nombre => $equipoJunior->nombre,
        ];
        $uri = "/torneo/".$torneoCreado->id;
        $response = $this->call('POST', $uri, $parametros);
        $this->assertEquals(302, $response->getStatusCode());

        $this->seeInDatabase(
            'torneos',
            [
             'anio' => '1981',
             'id_categoria' => $categoriaJunior->id,
            ]
        );
    }


    /**
     * Comprueba el funcionamiento para editar un torneo.
     * Se trata de modificar a un torneo que no existe(Prueba realizada por medio
     * de la url).
     * Es exitoso si permanece en la misma página principal de torneo.
     * Corresponde al caso de prueba testEditarTorneoURL.
     *
     * @return void
     */
    public function testEditarTorneo7()
    {
        $torneoUltimoRegistro = Torneo::orderBy('id', 'desc')->first();
        $idUltimoRegistro = $torneoUltimoRegistro->id+1;
        $this->visit('torneo/' . $idUltimoRegistro . '/edit')->seePageIs(route('torneo.index'));
    }
}
