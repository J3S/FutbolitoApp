<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipo;
use App\Categoria;
use App\Jugador;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EquipoController extends Controller
{
    /**
     * Display a listing of the 'Equipo'.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipos = Equipo::all();
        return view('equipo.equipo-index')->with(compact('equipos'));
    }

    /**
     * Show the form for creating a new 'Equipo'.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jugadores = Jugador::where('estado', 1)
                        ->get(['id', 'nombres', 'categoria']);
        $categorias = Categoria::all();
        return view('equipo.equipoc')->with('categorias', $categorias)
                                     ->with(compact('jugadores'));
    }

    /**
     * Store a newly created 'Equipo' in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $equipo = new Equipo();
        $equipo->nombre = $request->nombre;
        $equipo->director_tecnico = $request->entrenador;
        $equipo->categoria = $request->categoria;

        if ($equipo->save()) {
            return response()->json([
                "mensaje"=>"guardado con exito",
                "idEquipo"=>$equipo->id,
            ]);
        }
    }

    /**
     * Display the specified 'Equipo'.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo "show con id";
    }

    /**
     * Response the specifieds 'Jugadores'.
     *
     * @param  string  $categoria
     * @return \Illuminate\Http\Response (Json)
     */
    public function getJugadoresCategoria($categoria)
    {

        $jugadoresCategoria = Jugador::where('categoria', $categoria)->get(['id', 'nombres', 'categoria']);
        $arr = $jugadoresCategoria->toArray();
        if (!empty($jugadoresCategoria->toArray())) {
            return response()->json(
                $jugadoresCategoria->toArray()
            );
        }
        return 0;

    }

    /**
     * Show the form for editing the specified 'Equipo'.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $equipo = Equipo::find($id);
        echo "Forumulario para editar los datos del equipo: ".$equipo->id.' '.$equipo->nombre;
        //return view('equipo.equipoe');
    }

    /**
     * Update the specified 'Equipo' in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified 'Equipo' from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipo = Equipo::find($id);
        echo "desactivo en la BDD al equipo: ".$equipo->id.' '.$equipo->nombre;
        Flash::success(" se ha destruido");
    }
}
