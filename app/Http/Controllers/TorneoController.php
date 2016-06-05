<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Torneo;
use App\Equipo;
use App\Categoria;
use App\TorneoEquipo;

class TorneoController extends Controller
{
    /**
     * Muestra la vista principal de la opción torneo.
     * Devuelve la vista torneo junto con los torneos del
     * año actual
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Año actual del servidor
        $anioServer = date("Y");
        // Obtener todas las categorías de la base
        $categorias = Categoria::all();
        // Torneos del año actual
        $torneosExistentes = [];
        // Información de torneo individual
        $infoTorneo = [];
        $contadorInexistentes = 0;

        /**
         * Recorro el arreglo de categorías y busco el torneo de esa categoría del año actual.
         * Uniendo la información de ese torneo con el nombre de la categoría(si no existe el
         * torneo el id será 0) y colocando esa información en el arreglo con todos los torneos. 
         */
        foreach($categorias as $categoria) {
            $torneo = Torneo::where('id_categoria', $categoria->id)
                            ->where('anio', $anioServer)
                            ->where('estado',1)
                            ->first();
            if(count($torneo) == 0) {
                $contadorInexistentes++;
                $infoTorneo = ['id' => 0, 'categoria' => $categoria->nombre];
                array_push($torneosExistentes, $infoTorneo);
            } else {
                $infoTorneo = ['id' => $torneo->id, 'categoria' => $categoria->nombre];
                array_unshift($torneosExistentes, $infoTorneo);
            }
        }

        /**
         * Retorno la vista con todos los torneos del año actual, con el año actual del servidor,
         * el numero de torneos que no se han creado en el año, y todas las categorías.
         */
        return view('torneo')->with('torneos', $torneosExistentes)
                             ->with('anioServer', $anioServer)
                             ->with('inexistentes', $contadorInexistentes)
                             ->with('categorias', $categorias);
    }

    /**
     * Muestra la vista con el formulario para crear un
     * nuevo torneo.
     * Buscar todas las categorías creadas en la base devolviéndolas
     * con la vista.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtener todas las categorías de la base.
        $categorias = Categoria::all();

        // Búsqueda y división de los equipos por categorías.
        $equiposxcategorias = [];
        foreach ($categorias as $categoria) {
            $equipos = Equipo::where('categoria', $categoria->nombre)
                             ->get();
            $equiposxcategorias[$categoria->nombre] = $equipos;
        }

        // Retorno la vista con todas las categorías disponibles.
        return view('torneoc')->with('categorias', $categorias)
                              ->with('equiposxcategorias', $equiposxcategorias);
    }

    /**
     * Crea un nuevo torneo en la base.
     * Se verifica cuantos elementos tiene el $request para identificar
     * si hay equipos a ser enlazados con el torneo que se va a crear.
     * Si existe los equipos se los enlaza con el torneo usando la tabla
     * torneo_equipos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validación de los campos.
        $this->validate($request, [
            'categoria' => 'required',
            'anio' => 'required|numeric',
        ]);

        // Controlador de los valores a guardar en la base.
        $contador = 0;
        // Creación de un nuevo torneo.
        $torneo = new Torneo();

        foreach ($request->all() as $valor) {
            // Asignación del primer campo del torneo.
            if($contador == 1){
                $torneo->anio = $valor;
            }
            // Asignación del segundo campo del torneo y guardar el torneo en la base.
            if($contador == 2){
                $categoriaID = Categoria::where('nombre', $valor)
                                        ->get(['id'])
                                        ->toArray()[0]["id"];
                $torneo->id_categoria = $categoriaID;
                $torneo->estado = 1;
                $torneo->save();
            }
            /**
             * Crear y guardar los registros relacionados con los equipos participantes en el torneo
             * en la tabla torneo_equipos.
             */
            if($contador > 2){
                $torneoEquipo = new TorneoEquipo();
                $torneoEquipo->id_torneo = $torneo->id;
                $equipoID = Equipo::where('nombre', $valor)
                                  ->get(['id'])
                                  ->toArray()[0]["id"];
                $torneoEquipo->id_equipo = $equipoID;
                $torneoEquipo->save();
            }

            $contador++;
        }

        // Redirecciono a la ruta torneo.
        return redirect('torneo');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Muesta el formulario para editar la información del torneo seleccionado($id).
     * También muestra a todos los equipos que han sido agregados a ese torneo
     * dandole la opción de quitarlos del torneo o de agregar un nuevo equipo
     * al torneo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Obtener todas las categorías de la base.
        $categorias = Categoria::all();

        // Búsqueda y división de los equipos por categorías.
        $equiposxcategorias = [];
        foreach ($categorias as $categoria) {
            $equipos = Equipo::where('categoria', $categoria->nombre)
                             ->get();
            $equiposxcategorias[$categoria->nombre] = $equipos;
        }

        // Búsqueda del torneo con el id = $id.
        $torneo = Torneo::find($id);
        // Búsqueda de los registros en la tabla torneo_equipos que tienen id_torneo = $id.
        $torneoEquipos = TorneoEquipo::where('id_torneo',$id)
                                     ->get();

        // Búsqueda de los equipos que han sido agregados a ese torneo.
        $equiposAgregados = [];
        foreach ($torneoEquipos as $torneoEquipo) {
            $infoEquipo = Equipo::where('id', $torneoEquipo->id_equipo)
                                ->get();
            array_push($equiposAgregados, $infoEquipo[0]);
        }


        /**
         * Retorno la vista con la información del torneo seleccionado, con los equipos divididos
         * por categorías, todas las categorías registradas y con los equipos que están agregados
         * a ese torneo.
         */
        return view('torneoe')->with('torneo', $torneo)
                              ->with('equiposxcategorias', $equiposxcategorias)
                              ->with('categorias', $categorias)
                              ->with('equiposAgregados', $equiposAgregados);
    }

    /**
     * Actualiza la información de un torneo específico utilizando el $id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'categoria' => 'required',
            'anio' => 'required|numeric',
        ]);

        // Controlador de los valores que se van actualizar en la base.
        $contador = 0;
        // Buśqueda del torneo en la base.
        $torneo = Torneo::find($id);

        foreach ($request->all() as $valor) {
            // Asignación del primer campo del torneo
            if($contador == 2){
                $torneo->anio = $valor;
            }
            // Asignación del segundo campo del torneo y guardar el torneo en la base
            if($contador == 3){
                $categoriaID = Categoria::where('nombre', $valor)
                                        ->get(['id'])
                                        ->toArray()[0]["id"];
                $torneo->id_categoria = $categoriaID;
                $torneo->estado = 1;
                $torneo->save();
                // Eliminación de todos los registros relacionados a ese torneo en la tabla torneo_equipos
                $torneosEquiposEliminados = TorneoEquipo::where('id_torneo', $torneo->id)
                                                        ->delete();
            }
            /**
             * Crear y guardar los registros relacionados con los equipos participantes en el torneo
             * en la tabla torneo_equipos.
             */
            if($contador > 3){
                $torneoEquipo = new TorneoEquipo();
                $torneoEquipo->id_torneo = $torneo->id;
                $equipoID = Equipo::where('nombre', $valor)
                                  ->get(['id'])
                                  ->toArray()[0]["id"];
                $torneoEquipo->id_equipo = $equipoID;
                $torneoEquipo->save();
            }

            $contador++;
        }

        // Redirecciono a la ruta torneo.
        return redirect('torneo');
    }

    /**
     * Cambia el estado a 0, del torneo seleccionado identificado por el $id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $torneo = Torneo::find($id);
        $torneo->estado = 0;
        $torneo->save();
        return $this->index();
    }
}
