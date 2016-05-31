<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Torneo;
use App\Equipo;
use App\Partido;

class PartidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $torneos = Torneo::where('estado', 1)->get(['categoria', 'fechaInicio']);
        $equipos = Equipo::where('estado', 1)->get(['nombre']);
        return view('partidoc')->withTorneos($torneos)->withEquipos($equipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, array(
                'torneo' => 'required',
                'fecha' => 'required',
                'lugar' => 'required|max:200',
                'equipo_local' => 'required',
                'equipo_visitante' => 'required',
                'gol_local' => 'required',
                'gol_visitante' => 'required'
            ));
        
        $partido = new Partido;

        $partido->lugar = $request->lugar;
        $partido->fecha = $request->fecha;
        $torneo = Torneo::where('categoria', $request->torneo)->first(); // corregir
        $partido->id_torneo = $torneo->id;
        $partido->arbitro = $request->arbitro;
        $partido->observacion = $request->observaciones;
        $partido->gol_local = $request->gol_local;
        $partido->gol_visitante = $request->gol_visitante;
        $equipoLocal = Equipo::where('nombre', $request->equipo_local)->first();
        $partido->id_equipo = $equipoLocal->id;
        $equipoVisitante = Equipo::where('nombre', $request->equipo_visitante)->first();
        $partido->id_equipoV = $equipoVisitante->id;
        $partido->estado = 1;
        $partido->save();

        return redirect()->route('partido.create');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
