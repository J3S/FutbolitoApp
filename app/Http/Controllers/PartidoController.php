<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Torneo;
use App\Equipo;
use App\Partido;
use App\Categoria;

class PartidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();
        $equipos = Equipo::where('estado', 1)->get();
        $torneos = Torneo::where('estado', 1)->get();
        return view('partido')->withCategorias($categorias)
            ->withEquipos($equipos)->withTorneos($torneos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();
        $torneos = Torneo::where('estado', 1)->get(['anio', 'id_categoria']);
        $equipos = Equipo::where('estado', 1)->get(['nombre']);

        return view('partidoc')->withTorneos($torneos)
            ->withEquipos($equipos)
            ->withCategorias($categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
                'torneo' => 'required',
                'fecha' => 'required',
                'jornada' => 'required',
                'lugar' => 'required|max:200',
                'equipo_local' => 'required',
                'equipo_visitante' => 'required',
                'gol_local' => 'required',
                'gol_visitante' => 'required'
            ));
        
        $partido = new Partido;

        $partido->lugar = $request->lugar;
        $partido->fecha = $request->fecha;
        $torneoString = explode(" : ", $request->torneo);
        $categoria = Categoria::where('nombre', $torneoString[0])->first();
        $torneo = Torneo::where('anio', $torneoString[1])
            ->where('id_categoria', $categoria->id)->first();
        $partido->id_torneo = $torneo->id;
        $partido->jornada = $request->jornada;
        $partido->arbitro = $request->arbitro;
        $partido->observacion = $request->observaciones;
        $partido->gol_local = $request->gol_local;
        $partido->gol_visitante = $request->gol_visitante;
        $partido->equipo_local = $request->equipo_local;
        $partido->equipo_visitante = $request->equipo_visitante;
        $partido->estado = 1;
        $partido->save();

        return redirect('partido');
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
        $partido = Partido::find($id);
        $date = date("Y-m-d\TH:i:s", strtotime($partido->fecha));
        $torneos = Torneo::where('estado', 1)->get();
        $torneo = Torneo::find($partido->id_torneo);
        $categoria = Categoria::find($torneo->id_categoria);
        $categorias = Categoria::all();
        $equipos = Equipo::where('estado', 1)->get(['id', 'nombre']);
        $equipo = Equipo::where('nombre', $partido->equipo_local)->get();
        $equipoV = Equipo::where('nombre', $partido->equipo_visitante)->get();
        
        return view('partidoe')->withEquipo($equipo)
            ->withEquipoV($equipoV)->withPartido($partido)
            ->withTorneos($torneos)->withEquipos($equipos)
            ->withDate($date)->withCategorias($categorias);
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
        $this->validate($request, array(
            'torneo' => 'required',
            'fecha' => 'required',
            'lugar' => 'required|max:200',
            'equipo_local' => 'required',
            'equipo_visitante' => 'required',
            'gol_local' => 'required',
            'gol_visitante' => 'required'
        ));
        
        $partido = Partido::find($id);

        $partido->lugar = $request->lugar;
        $partido->fecha = $request->fecha;
        $torneoString = explode(" : ", $request->torneo);
        $categoria = Categoria::where('nombre', $torneoString[0])->first();
        $torneo = Torneo::where('anio', $torneoString[1])
            ->where('id_categoria', $categoria->id)->first();
        $partido->id_torneo = $torneo->id;
        $partido->arbitro = $request->arbitro;
        $partido->observacion = $request->observaciones;
        $partido->gol_local = $request->gol_local;
        $partido->gol_visitante = $request->gol_visitante;
        $partido->equipo_local = $request->equipo_local;
        $partido->equipo_visitante = $request->equipo_visitante;
        $partido->estado = 1;
        $partido->save();

        return redirect('partido');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $partido = Partido::find($id);
        $partido->estado = 0;
        $partido->save();

        return $this->index();
    }

    public function searchByDate(Request $request) {
        $partidos = Partido::where('estado', 1)->get();

        if($request->ini_partido != "" && $request->fin_partido != ""){
            $partidosFecha = Partido::whereBetween('fecha', array($request->ini_partido, $request->fin_partido))
            ->where('estado', 1)->get();
            $partidos = $partidos->intersect($partidosFecha);
        }
        
        if($request->torneo != ""){
            $torneoString = explode(" : ", $request->torneo);
            $categoria = Categoria::where('nombre', $torneoString[0])->first();
            $torneo = Torneo::where('anio', $torneoString[1])
                ->where('id_categoria', $categoria->id)->first();
            $partidosTorneo = Partido::where('id_torneo', $torneo->id)->get();
            $partidos = $partidos->intersect($partidosTorneo);
        }

        if($request->jornada != ""){
            $partidosJornada = Partido::where('jornada', $request->jornada)->get();
            $partidos = $partidos->intersect($partidosJornada);
        }

        if($request->equipo_local != ""){
            $partidosEquipoLocal = Partido::where('equipo_local', $request->equipo_local)->get();
            $partidos = $partidos->intersect($partidosEquipoLocal);
        }

        if($request->equipo_visitante != ""){
            $partidosEquipoVisitante = Partido::where('equipo_visitante', $request->equipo_visitante)->get();
            $partidos = $partidos->intersect($partidosEquipoVisitante);
        }

        $equipos = Equipo::where('estado', 1)->get();
        $torneos = Torneo::where('estado', 1)->get();
        $categorias = Categoria::all();

        return view('partido')
            ->withPartidos($partidos)
            ->withEquipos($equipos)
            ->withTorneos($torneos)
            ->withCategorias($categorias);
    }
}
