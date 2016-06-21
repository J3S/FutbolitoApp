<?php
/**
 * Controlador para Jugador
 *
 * @category   PHP
 * @package    Laravel
 * @subpackage Controller
 * @author     Angely Oyola <ajazzita@gmail.com>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Torneo;
use App\TorneoEquipo;
use App\Equipo;
use App\Partido;
use App\Categoria;
use App\Jugador;
use Flash;

/**
 * Clase JugadorController
 *
 * @category   PHP
 * @package    Laravel
 * @subpackage Controller
 * @author     Kevin Filella <kfl0202@gmail.com>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
class JugadorController extends Controller
{
    /**
     * Display lista de Jugadores
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipos = Equipo::where('estado', 1)->get();
        $categorias = Categoria::all();

       /* 
        * Retorno a la vista principal la lista de equipos
       */
        return view('jugador')->withEquipos($equipos)->withCategorias($categorias);
    }//end index

    /**
     * Prepara la vista para la creacion de los jugadores.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
    
       /* 
        *Recorro listas de equipos para que sean escogidos por el jugador.
       */

        $equipos = Equipo::where('estado', 1)->get();
        $categorias = Categoria::all();

        /* 
         *Retorno la vista para crear partido con la lista de torneos, equipos y categorias
        */

        return view('jugadorc')->withEquipos($equipos)->withCategorias($categorias);
    }//end create

    /**
     * Función que se encarga de crear un nuevo jugador en la base de datos, utilizando los datos
     * ingresados por el cliente desde la vista 'jugadorc'.
     *
     * @param \Illuminate\Http\Request $request Contiene informacion sobe el jugador que sera creado
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
     {

        /* Validación de datos requeridos para la creacion de un jugador */
        $this->validate(
            $request, 
            array(
            'nombres' => 'required|regex:/([A-Z a-z])+/',
            'apellidos' => 'required|regex:/([A-Z a-z])+/',
            'equipo' => 'required',
            'identificacion' => 'required|numeric|unique:jugadors,identificacion|digits_between:10,13',
            'num_camiseta' => 'numeric|between:01,99',
            'rol' => 'alpha',
            'peso' => 'numeric',
            'telefono' => 'numeric',
            'email' => 'email',
            'fecha_nac' => 'date'
            )
        );

        /* 
        *Creo nueva instancia de jugador y le asigno todos los valores 
        *ingresados por el usuario desde la vista 'jugadorc' 
        */
        $jugador = new Jugador;
        $jugador->nombres = $request->nombres;
        $jugador->apellidos = $request->apellidos;
        $jugador->fecha_nac = $request->fecha_nac;
        $jugador->identificacion = $request->identificacion;
        $jugador->rol = $request->rol;
        $jugador->email = $request->email;
        $jugador->telefono = $request->telefono;
        $jugador->peso = $request->peso;
        $jugador->num_camiseta = $request->num_camiseta;
        $jugador->categoria = $request->categoria;
        $jugador->estado = 1;
        $jugador->id_equipo =  $request->equipo;

        // Guardo el jugador creado en la base de datos 
        $jugador->save();
        flash()->info('Jugador ha sido creado con éxito.');

        /* Retorno a la vista principal de la opcion jugador */       
        return $this->index();
    }

    /**
     * Display the specified Jugador.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }//end show()

    /**
     * Prepara la vista para la modificación de un jugador.
     *
     * @param int $id ID del jugador que se desea editar
     *
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        // Encuentro el jugador seleccionado por el usuario 
        $jugador = Jugador::find($id);

        // Preparo los datos que seran enviados a la vista 
        $equipos = Equipo::where('estado', 1)->get(['id', 'nombre']);
        $categorias = Categoria::all();
          
        /* Retorno vista para editar partido con la información del partido seleccionado
         *, lista de equipos, lista de torneos y lista de categorias 
         */

        return view('jugadore')->withEquipos($equipos)->withCategorias($categorias)->withJugador($jugador);
    }//end edit()

    /**
     * Función que se encarga de actualizar un jugador en la base de datos, utilizando los datos
     * ingresados por el cliente desde la vista 'jugadore'.
     *
     * @param \Illuminate\Http\Request $request Contiene informacion que se actualizara en el jugador
     * @param int                      $id      ID del jugador que se va a actualizar
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $jugador = Jugador::find($id);        // Validación de datos requeridos para la creacion de un jugador 
        $this->validate(
           $request, 
           array(
            'nombres' => 'required|regex:/([A-Z a-z])+/',
            'apellidos' => 'required|regex:/([A-Z a-z])+/',
            'equipo' => 'required',
            'identificacion' => 'required|numeric|digits_between:10,13|unique:jugadors,identificacion,'.$jugador->id,
            'num_camiseta' => 'numeric|between:01,99',
            'rol' => 'alpha',
            'peso' => 'numeric',
            'telefono' => 'numeric',
            'email' => 'email',
            'fecha_nac' => 'date'
            )
        );

        /* Creo nueva instancia de jugador y le asigno todos los valores ingresados 
         * por el usuario desde la vista 'jugadorc' 
        */
        $jugador->nombres = $request->nombres;
        $jugador->apellidos = $request->apellidos;
        $jugador->fecha_nac = $request->fecha_nac;
        $jugador->identificacion = $request->identificacion;
        $jugador->rol = $request->rol;
        $jugador->email = $request->email;
        $jugador->telefono = $request->telefono;
        $jugador->peso = $request->peso;
        $jugador->num_camiseta = $request->num_camiseta;
        $jugador->categoria = $request->categoria;
        $jugador->estado = 1;
        $equipo = Equipo::find($request->equipo);
        $jugador->id_equipo = $equipo->id;


        // Guardo el jugador creado en la base de datos 
        $jugador->save();
        flash()->info('Jugador ha sido modificado con éxito.');

        // Retorno a la vista principal de la opcion jugador 
        return $this->index();
    }//end update()


    /**
     * Función que se encarga de desactivar un jugador en la base de datos.
     *
     * @param int $id ID del jugador que se desea desactivar
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Encuentro al jugador que el usuario desea desactivar y cambio su estado a 0 (desactivado) 
        $jugador = Jugador::find($id);
        $jugador->estado = 0;

        //Actualizo los datos del jugador en la base de datos 
        $jugador->save();
        flash()->info('Jugador ha sido borrado con éxito.');

        return $this->index();
    }//end destroy()


    /**
     * Función que se encarga de filtrar los jugadores activos utilizando los datos ingresados por el
     * usuario en el formulario de búsqueda de la vista principal de jugador.
     *
     * @param \Illuminate\Http\Request $request Contiene informacion para la busqueda del jugador
     *
     * @return \Illuminate\Http\Response
     */

    public function searchJugador(Request $request) {
       
        // Encuentro todos los jugadores 
        $jugadores = Jugador::where('estado', 1)->get();

        // Busco equipos activos
        $equipos = Equipo::where('estado', 1)->get();

        // Busco todas las categorias
        $categorias = Categoria::all();

        // Filtro los jugadores por nombre (si es que el filtro fue ingresado por el usuario) 
        if($request->nombJug != ""){
            $jugadoresNombre = Jugador::where('nombres', 'like', '%' . $request->nombJug . '%')->get();
            $jugadores = $jugadores->intersect($jugadoresNombre);
        }

        // Filtro los jugadores por apellidos (si es que el filtro fue ingresado por el usuario) 
        if($request->apellJug != ""){
            $jugadoresApellido = Jugador::where('apellidos', 'like', '%' . $request->apellJug . '%')->get();
            $jugadores = $jugadores->intersect($jugadoresApellido);
        }

        // Filtro los jugadores por cedula (si es que el filtro fue ingresado por el usuario) 
        if($request->cedJug != ""){
            $jugadoresCedula = Jugador::where('identificacion', 'like', '%' . $request->cedJug . '%')->get();
            $jugadores = $jugadores->intersect($jugadoresCedula);
        }

        // Filtro los jugadores por el equipo al que pertenecen (si es que el filtro fue ingresado por el usuario) 
        if($request->equipo != ""){
            $equipo = Equipo::find($request->equipo);
            $jugadoresEquipo = Jugador::where('id_equipo', $equipo->id)->get();
            $jugadores = $jugadores->intersect($jugadoresEquipo);
        }

        // Filtro los jugadores por la categoria a la que pertenecen (si es que el filtro fue ingresado por el usuario) 
        if($request->categoria != ""){
            $categoria = Categoria::find($request->categoria);
            $jugadoresCategoria = Jugador::where('categoria', $categoria->nombre)->get();
            $jugadores = $jugadores->intersect($jugadoresCategoria);
        }
       
        /* Retorno a vista principal de jugador con los jugadores filtrados y lista de equipos activos*/
        return view('jugador')->withJugadores($jugadores)->withEquipos($equipos)->withCategorias($categorias);
   }//end searchPartido()


}//end class
