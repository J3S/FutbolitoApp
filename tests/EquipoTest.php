<?php
/**
 * This file contains common functions used for testing when user creates Equipo.
 * PHP version 5
 *
 * @category Testing
 * @package  tests
 * @author   Branny Chito <brajchit@espol.edu.ec>
 * @license  http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @link     http://definirlink.local
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Categoria;
use App\Jugador;
use App\Usuario;

/**
 * EquipoTest Class Doc Comment
 *
 * @category PHPunit
 * @package  Test
 * @author   Branny Chito <brajchit@espol.edu.ec>
 * @license  MIT, http://opensource.org/licenses/MIT
 * @link     http://definirlink.local
 */
class EquipoTest extends TestCase
{


    use DatabaseTransactions;


    /**
     * Comprueba el funcionamiento para la primera ventana de Equipo.
     * Se entra al submenu Equipo con metodo Get,
     * Es exitoso si el sistema muestra el formulario para buscar equipo
     *
     * @return void
     */
    public function testEquipoIndex()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $this->visit(route('equipo.index'))
             ->see('Buscar Equipo');

    }// end testEquipoIndex()


    /**
     * Comprueba el funcionamiento editar un requipo Equipo.
     * Se entra al submenu Equipo busca un equipo clik en editar,
     * Es exitoso si el sistema muestra el formulario para editar equipo
     *
     * @return void
     */
    public function testEquipoEdit()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $this->visit('equipo/1/edit')
             ->see('Editar Equipo');

    }// end testEquipoEdit()


    /**
     * Comprueba el funcionamiento para buscar un Equipo.
     * Se entra al submenu Equipo con metodo Get,
     * Es exitoso si el sistema muestra una lista de equipos.
     *
     * @return void
     */
    public function testEquipoSearch1()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nomToSearch = 'A';
        $catToSearch = '3';
        $this->visit(route('equipo.index'))
             ->type($nomToSearch, 'nombEquipo')
             ->select($catToSearch, 'categoria')
             ->press('btn-search')
             ->see('Lista de Equipos');

    }// end testEquipoSearch1()


    /**
     * Comprueba el funcionamiento para buscar un Equipo.
     * Se entra al submenu Equipo con metodo Get,
     * Es exitoso si el sistema muestra una lista de equipos.
     *
     * @return void
     */
    public function testEquipoSearch2()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nomToSearch = '';
        $catToSearch = '3';
        $this->visit(route('equipo.index'))
             ->type($nomToSearch, 'nombEquipo')
             ->select($catToSearch, 'categoria')
             ->press('btn-search')
             ->see('Lista de Equipos');

    }// end testEquipoSearch2()

    /**
     * Comprueba el funcionamiento para mostrar un Equipo.
     * Se entra al submenu Equipo con metodo Get,
     * Es exitoso si el sistema muestra el perfil de Equipo
     *
     * @return void
     */
    public function testEquipoShow1()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $response = $this->call('GET', '/equipo/1');
        $this->see('Perfil de Equipo');


    }// end testEquipoShow1()


    /**
     * Comprueba el funcionamiento para mostrar un Equipo.
     * Se hace un requerimiento Get con AJAX, con un idEquipo,
     * Es exitoso si el sistema muestra el perfil de Equipo
     *
     * @return void
     */
    public function testEquipoShow2()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);

        $idEquipo = '1';
        $response = $this->call(
            'GET',
            'equipo/'.$idEquipo,
            array(),
            array(), // Cookies
            array(), // Files
            ['HTTP_X-Requested-With' => 'XMLHttpRequest'] // Serve Ajax
        );
        $this->seeJson(["apellidos" => "ALONSO ALVAREZ"]);


    }// end testEquipoShow2()


    /**
     * Comprueba el funcionamiento retronar un JSON.
     * Hace un requerimiento get a la ruta equipo/ con una categoria,
     * Es exitoso si el sistema responde con Ok.
     *
     * @return void
     */
    public function testEquipoGetJugs()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);

        $categoria = 'Master';
        $this->call('GET', 'jugadores/'.$categoria);
        $this->assertResponseOk();


    }// end testEquipoGetJugs()


}
