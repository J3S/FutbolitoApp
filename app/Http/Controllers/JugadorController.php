<?php

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

class JugadorController extends Controller
{
    /**
     * Display a listing of the Jugador.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipos = Equipo::where('estado', 1)->get();
        $categorias = Categoria::all();
       /* Retorno a la vista principal la lista de equipos
        */
        return view('jugador')->withEquipos($equipos)->withCategorias($categorias);
    }

    /**
     * Prepara la vista para la creacion de los jugadores.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
    
       /* Recorro listas de equipos para que sean escogidos por el jugador.
        */
        $equipos = Equipo::where('estado', 1)->get();
        $categorias = Categoria::all();

        /* Retorno la vista para crear partido con la lista de torneos, equipos y categorias
        */
        return view('jugadorc')->withEquipos($equipos)->withCategorias($categorias);
    }

    /**
     * Store a newly created Jugador in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
     {
        /* Validación de datos requeridos para la creacion de un jugador */
        $this->validate($request, array(
                'nombres' => 'required',
                'apellidos' => 'required',
                'equipo' => 'required',
                'identificacion' => 'required',
                'categoria' => 'required',
                'identificacion'=> 'unique:jugadors,identificacion',
                'num_camiseta' => 'unique:jugadors,num_camiseta, equipo'
            ));
        /* Creo nueva instancia de jugador y le asigno todos los valores ingresados por el usuario desde la vista 'jugadorc' */
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
        /* Guardo el jugador creado en la base de datos */
        $jugador->save();
        /* Retorno a la vista principal de la opcion partido */
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
        //
    }

    /**
     * Show the form for editing the specified Jugador.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /* Encuentro el jugador seleccionado por el usuario */
        $jugador = Jugador::find($id);

        /* Preparo los datos que seran enviados a la vista */

        $equipos = Equipo::where('estado', 1)->get(['id', 'nombre']);
        $categorias = Categoria::all();
        
        
        /* Retorno vista para editar partido con la información del partido seleccionado, lista de equipos, lista de torneos y lista de categorias */
        return view('jugadore')->withEquipos($equipos)->withCategorias($categorias)->withJugador($jugador);
    }

    /**
     * Update the specified Jugador in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         /* Validación de datos requeridos para la creacion de un jugador */
        $this->validate($request, array(
                'nombres' => 'required',
                'apellidos' => 'required',
                'equipo' => 'required'
            ));

        /* Creo nueva instancia de jugador y le asigno todos los valores ingresados por el usuario desde la vista 'jugadorc' */
        $jugador = Jugador::find($id);
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


        /* Guardo el partido creado en la base de datos */
        $jugador->save();

        /* Retorno a la vista principal de la opcion partido */
        return $this->index();
    }

    /**
     * Función que se encarga de desactivar un jugador en la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* Encuentro al jugador que el usuario desea desactivar y cambio su estado a 0 (desactivado) */
        $jugador = Jugador::find($id);
        $jugador->estado = 0;

        /* Actualizo los datos del partido en la base de datos */
        $jugador->save();

        return $this->index();
    }
    /**
     * Función que se encarga de filtrar los jugadores utilizando los parámetros ingresados por el
     * usuario en el formulario de búsqueda.
     */
    public function searchJugador(Request $request) {
        /* Encuentro todos los jugadores */
        $jugadores = Jugador::where('estado', 1)->get();
        //$equipos_jugadores = $jugadores->join('equipos', 'equipos.id', '=', 'jugadores.id_equipo');
        /* Busco equipos*/
        $equipos = Equipo::where('estado', 1)->get();
        /* Busco categrias*/
        $categorias = Categoria::all();

        /* Filtro los jugadores por nombre (si es que el filtro fue ingresado por el usuario) */
        if($request->nombJug != ""){
            $jugadoresNombre = Jugador::where('nombres', 'like', '%' . $request->nombJug . '%')->get();
            $jugadores = $jugadores->intersect($jugadoresNombre);
        }

        /* Filtro los jugadores por apellidos (si es que el filtro fue ingresado por el usuario) */
        if($request->apellJug != ""){
            $jugadoresApellido = Jugador::where('apellidos', 'like', '%' . $request->apellJug . '%')->get();
            $jugadores = $jugadores->intersect($jugadoresApellido);
        }

        /* Filtro los jugadores por cedula (si es que el filtro fue ingresado por el usuario) */
        if($request->cedJug != ""){
            $jugadoresCedula = Jugador::where('identificacion', 'like', '%' . $request->cedJug . '%')->get();
            $jugadores = $jugadores->intersect($jugadoresCedula);
        }

        if($request->equipo != ""){
            $equipo = Equipo::find($request->equipo);
            $jugadoresEquipo = Jugador::where('id_equipo', $equipo->id)->get();
            $jugadores = $jugadores->intersect($jugadoresEquipo);
        }

        if($request->categoria != ""){
            $categoria = Categoria::find($request->categoria);
            $jugadoresCategoria = Jugador::where('categoria', $categoria->nombre)->get();
            $jugadores = $jugadores->intersect($jugadoresCategoria);
        }
       
        /* Retorno a vista principal de jugador con los jugadores filtrados y lista de equipos activos*/
        return view('jugador')->withJugadores($jugadores)->withEquipos($equipos)->withCategorias($categorias);
    }
}
