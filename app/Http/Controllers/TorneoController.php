<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Torneo;
use App\Equipo;

class TorneoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $torneos = Torneo::where('estado', 1)
                        ->get(['id', 'categoria', 'fechaInicio', 'fechaFin']);
        return view('torneo')->with('torneos', $torneos);
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
        return view('torneoc')->with('equipos', $equipos);;
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
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date',
        ]);

        $torneo = new Torneo();
        $torneo->categoria = $request->categoria;
        $torneo->fechaInicio = $request->fechaInicio;
        $torneo->fechaFin = $request->fechaFin;
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
        dd($id);
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
        $torneo = Torneo::find($id);
        $torneo->estado = 0;
        $torneo->save();
        return $this->index();
    }
}
