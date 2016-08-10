<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Categoria;
use App\Equipo;
use App\Torneo;
use App\Jugador;
use App\TorneoEquipo;
use Carbon\Carbon;
use App\Usuario;

class BuscarJugadorTest extends TestCase
{

	use DatabaseTransactions;

    /**
     * Comprueba el funcionamiento de buscar un partido dado el filtro de nombres
     * 
     * @return void
     */
    public function testBuscarJugador1()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = 'ADRIANO PEDRO';
        $this->visit(route('jugador.index'))
            ->type($nombres, 'nombJug')        
            ->press('btnFormBuscarJugador')
            ->seePageIs('/selectJugador');
    }

   /**
     * Comprueba el funcionamiento de buscar un partido dado el filtro de apellidos
     * 
     * @return void
     */
    public function testBuscarJugador2()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $apellidos = 'DELGADO RAMOS';
        $this->visit(route('jugador.index'))
            ->type($apellidos, 'apellJug')        
            ->press('btnFormBuscarJugador')
            ->seePageIs('/selectJugador');
    }

   /**
     * Comprueba el funcionamiento de buscar un partido dado el filtro de cédula
     * 
     * @return void
     */
    public function testBuscarJugador3()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $cedula = '1358989238';
        $this->visit(route('jugador.index'))
            ->type($cedula, 'cedJug')        
            ->press('btnFormBuscarJugador')
            ->seePageIs('/selectJugador');
    }

   /**
     * Comprueba el funcionamiento de buscar un partido dado el filtro de categoría
     * 
     * @return void
     */
    public function testBuscarJugador4()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $categoria = Categoria::where('nombre', "Rey Master")->first();
        $this->visit(route('jugador.index'))
            ->select($categoria->id, 'categoria')      
            ->press('btnFormBuscarJugador')
            ->seePageIs('/selectJugador');
    }

   /**
     * Comprueba el funcionamiento de buscar un partido dado el filtro de equipo
     * 
     * @return void
     */
    public function testBuscarJugador5()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $equipo = Equipo::where('nombre', '20B')->first();
        $this->visit(route('jugador.index'))
            ->select($equipo->id, 'equipo')      
            ->press('btnFormBuscarJugador')
            ->seePageIs('/selectJugador');
    }



   /**
     * Comprueba el funcionamiento de buscar un partido dando filtros de nombres y apellidos existentes
     * 
     * @return void
     */
    public function testBuscarJugador6()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = 'ADRIANO PATRICIO';
        $apellidos = 'ORTIZ BRAVO';	
        $this->visit(route('jugador.index'))  
            ->type($nombres, 'nombJug')  
            ->type($apellidos, 'apellJug') 
            ->press('btnFormBuscarJugador')
            ->seePageIs('/selectJugador');
    }

   /**
     * Comprueba el funcionamiento de buscar un partido dando filtros de nombres y apellidos no existentes
     * 
     * @return void
     */
    public function testBuscarJugador7()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = 'DELFIN PATRICIO';
        $apellidos = 'ORTIZ QUISHPE';	
        $this->visit(route('jugador.index'))  
            ->type($nombres, 'nombJug')  
            ->type($apellidos, 'apellJug') 
            ->press('btnFormBuscarJugador')
            ->seePageIs('/selectJugador');
    }

   /**
     * Comprueba el funcionamiento de buscar un partido dando filtros de nombres, apellidos Y cédula existentes
     * 
     * @return void
     */
    public function testBuscarJugador8()
    {
        $user = new Usuario(['user' => 'admin']);
        $this->be($user);
        $nombres = 'ADRIANO PATRICIO';
        $apellidos = 'ORTIZ BRAVO';	
        $cedula = '1358782325';
        $this->visit(route('jugador.index'))  
            ->type($nombres, 'nombJug')  
            ->type($apellidos, 'apellJug') 
            ->type($cedula, 'cedJug')   
            ->press('btnFormBuscarJugador')
            ->seePageIs('/selectJugador');
    }
    
    
}

