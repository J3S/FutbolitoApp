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
        $this->visit(route('equipo.index'))
             ->see('Buscar Equipo');

    }// end testEquipoIndex()


    /**
     * Comprueba el funcionamiento para la primera ventana de Equipo.
     * Se entra al submenu Equipo con metodo Get,
     * Es exitoso si el sistema muestra el formulario para buscar equipo
     *
     * @return void
     */
    public function testEquipoSearch1()
    {
        $this->visit(route('equipo.search'))
             ->press()
             ->see('Buscar Equipo');

    }// end testEquipoIndex()


}
