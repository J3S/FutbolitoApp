<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Torneo;
use App\Equipo;
use App\Categoria;

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
         * el numero de torneos que no se han creado en el año, y todas las categorías
         */
        return view('torneo')->with('torneos', $torneosExistentes)
                             ->with('anioServer', $anioServer)
                             ->with('inexistentes', $contadorInexistentes)
                             ->with('categorias', $categorias);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $equipos = Equipo::where('estado', 1)
                        ->get(['id', 'nombre']);
        return view('torneoc')->with('equipos', $equipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'categoria' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);

        $torneo = new Torneo();
        $torneo->categoria = $request->categoria;
        $torneo->fecha_inicio = $request->fecha_inicio;
        $torneo->fecha_fin = $request->fecha_fin;
        $torneo->estado = 1;
        $torneo->save();
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $torneo = Torneo::find($id);
        $equipos = Equipo::where('estado', 1)
                        ->get(['id', 'nombre']);
        return view('torneoe')->with('torneo', $torneo)->with('equipos', $equipos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'categoria' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);

        $torneo = Torneo::find($id);
        $torneo->categoria = $request->categoria;
        $torneo->fecha_inicio = $request->fecha_inicio;
        $torneo->fecha_fin = $request->fecha_fin;
        $torneo->save();
        return redirect('torneo');
    }

    /**
     * Remove the specified resource from storage.
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
