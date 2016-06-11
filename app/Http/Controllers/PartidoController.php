<?php
/**
 * Controlador para partidos
 *
 * @category   PHP
 * @package    Laravel
 * @subpackage Controller
 * @author     Kevin Filella <kfl0202@gmail.com>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Torneo;
use App\TorneoEquipo;
use App\Equipo;
use App\Partido;
use App\Categoria;
/**
 * Clase PartidoController
 *
 * @category   PHP
 * @package    Laravel
 * @subpackage Controller
 * @author     Kevin Filella <kfl0202@gmail.com>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
class PartidoController extends Controller
{


    /**
     * Prepara la vista principal de la opción partido.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias    = Categoria::all();
        $equipos       = Equipo::where('estado', 1)->get();
        $torneos       = Torneo::where('estado', 1)->get();
        $torneoEquipos = TorneoEquipo::all();

        /*
            * Retorno a la vista principal Partido con los siguientes parámetros: listas de categorias, equipos y
            * torneos presentes en la base de datos.
        */

        return view('partido')->withCategorias($categorias)
            ->withEquipos($equipos)->withTorneos($torneos)->with('torneoEquipos', $torneoEquipos);

    }//end index()


    /**
     * Prepara la vista para la creacion de los partidos.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*
            * Recorro listas de categorias, torneos y equipos para mandarlos como parametros a la vista.
        */

        $categorias    = Categoria::all();
        $torneos       = Torneo::where('estado', 1)->get();
        $equipos       = Equipo::where('estado', 1)->get();
        $torneoEquipos = TorneoEquipo::all();

        /*
            * Retorno la vista para crear partido con la lista de torneos, equipos y categorias
        */

        return view('partidoc')->withTorneos($torneos)
            ->withEquipos($equipos)->withCategorias($categorias)
            ->with('torneoEquipos', $torneoEquipos);

    }//end create()


    /**
     * Función que se encarga de crear un nuevo partido en la base de datos, utilizando los datos
     * ingresados por el cliente desde la vista 'partidoc'.
     *
     * @param \Illuminate\Http\Request $request Contiene informacion sobe el partido que sera creado
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validación de datos requeridos para la creacion de un partido.
        $this->validate(
            $request,
            array(
             'torneo'           => 'required',
             'fecha'            => 'required',
             'jornada'          => 'required',
             'lugar'            => 'required|max:200',
             'equipo_local'     => 'required',
             'equipo_visitante' => 'required',
             'gol_local'        => 'required',
             'gol_visitante'    => 'required',
            )
        );

        // Creo nueva instancia de partido y le asigno todos los valores ingresados por el usuario desde la vista 'partidoc'.
        $partido            = new Partido();
        $partido->lugar     = $request->lugar;
        $partido->fecha     = $request->fecha;
        $partido->id_torneo = $request->torneo;
        $partido->jornada   = $request->jornada;
        $partido->arbitro   = $request->arbitro;
        $partido->observacion   = $request->observaciones;
        $partido->gol_local     = $request->gol_local;
        $partido->gol_visitante = $request->gol_visitante;
        $equipoLocal            = Equipo::find($request->equipo_local);
        $partido->equipo_local  = $equipoLocal->nombre;
        $equipoVisitante        = Equipo::find($request->equipo_visitante);
        $partido->equipo_visitante = $equipoVisitante->nombre;
        $partido->estado           = 1;

        // Guardo el partido creado en la base de datos.
        $partido->save();

        // Retorno a la vista principal de la opcion partido.
        return $this->index();

    }//end store()


    /**
     * Display the specified resource.
     *
     * @param int $id ID del partido que se desea mostrar
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }//end show()


    /**
     * Prepara la vista para la modificación de un partido.
     *
     * @param int $id ID del partido que se desea editar
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Encuentro el partido seleccionado por el usuario.
        $partido = Partido::find($id);

        // Preparo los datos que seran enviados a la vista.
        $date          = date("Y-m-d\TH:i:s", strtotime($partido->fecha));
        $torneos       = Torneo::where('estado', 1)->get();
        $torneo        = Torneo::find($partido->id_torneo);
        $categoria     = Categoria::find($torneo->id_categoria);
        $categorias    = Categoria::all();
        $equipos       = Equipo::where('estado', 1)->get(['id', 'nombre']);
        $equipo        = Equipo::where('nombre', $partido->equipo_local)->get();
        $equipoV       = Equipo::where('nombre', $partido->equipo_visitante)->get();
        $torneoEquipos = TorneoEquipo::all();

        // Retorno vista para editar partido con la información del partido seleccionado, lista de equipos, lista de torneos y lista de categorias.
        return view('partidoe')->withEquipo($equipo)
            ->withEquipoV($equipoV)->withPartido($partido)
            ->withTorneos($torneos)->withEquipos($equipos)
            ->withDate($date)->withCategorias($categorias)
            ->with('torneoEquipos', $torneoEquipos);

    }//end edit()


    /**
     * Función que se encarga de actualizar un partido en la base de datos, utilizando los datos
     * ingresados por el cliente desde la vista 'partidoe'.
     *
     * @param \Illuminate\Http\Request $request Contiene informacion que se actualizara en el partido
     * @param int                      $id      ID del partido que se va a actualizar
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validación de datos requeridos para la modificación de un partido.
        $this->validate(
            $request,
            array(
             'torneo'           => 'required',
             'fecha'            => 'required',
             'lugar'            => 'required|max:200',
             'equipo_local'     => 'required',
             'equipo_visitante' => 'required',
             'gol_local'        => 'required',
             'gol_visitante'    => 'required',
            )
        );

        // Encuentro el partido seleccionado por el usuario y modifico todos sus valores por los valores ingresados por el usuario desde la vista 'partidoe'.
        $partido            = Partido::find($id);
        $partido->lugar     = $request->lugar;
        $partido->fecha     = $request->fecha;
        $partido->id_torneo = $request->torneo;
        $partido->arbitro   = $request->arbitro;
        $partido->observacion   = $request->observaciones;
        $partido->jornada       = $request->jornada;
        $partido->gol_local     = $request->gol_local;
        $partido->gol_visitante = $request->gol_visitante;
        $equipoLocal            = Equipo::find($request->equipo_local);
        $partido->equipo_local  = $equipoLocal->nombre;
        $equipoVisitante        = Equipo::find($request->equipo_visitante);
        $partido->equipo_visitante = $equipoVisitante->nombre;
        $partido->estado           = 1;

        // Actualizo la información del partido en la base de datos.
        $partido->save();

        // Retorno a la vista principal de la opción partido.
        return $this->index();

    }//end update()


    /**
     * Función que se encarga de desactivar un partido en la base de datos.
     *
     * @param int $id ID del partido que se desea desactivar
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Encuentro el partido que el usuario desea desactivar y cambio su estado a 0 (desactivado).
        $partido         = Partido::find($id);
        $partido->estado = 0;

        // Actualizo los datos del partido en la base de datos.
        $partido->save();

        return $this->index();

    }//end destroy()


    /**
     * Función que se encarga de filtrar los partidos activos utilizando los datos ingresados por el
     * usuario en el formulario de búsqueda de la vista principal de partido.
     *
     * @param \Illuminate\Http\Request $request Contiene informacion para la busqueda del partido
     *
     * @return \Illuminate\Http\Response
     */
    public function searchPartido(Request $request)
    {
        // Encuentro todos los partidos activos.
        $partidos = Partido::where('estado', 1)->get();

        // Filtro los partidos activos por fecha inicial y final (si fueron ingresados por el usuario).
        if ($request->ini_partido !== '' && $request->fin_partido !== '') {
            $partidosFecha = Partido::whereBetween('fecha', array($request->ini_partido, $request->fin_partido))
            ->where('estado', 1)->get();
            $partidos      = $partidos->intersect($partidosFecha);
        }

        // Filtro los partidos activos por torneo (si fue ingresado por el usuario).
        if ($request->torneo !== '') {
            $torneo         = Torneo::find($request->torneo);
            $partidosTorneo = Partido::where('id_torneo', $torneo->id)->get();
            $partidos       = $partidos->intersect($partidosTorneo);
        }

        // Filtro los partidos activos por jornada (si fue ingresado por el usuario).
        if ($request->jornada !== '') {
            $partidosJornada = Partido::where('jornada', $request->jornada)->get();
            $partidos        = $partidos->intersect($partidosJornada);
        }

        // Filtro los partidos activos por equipo local (si fue ingresado por el usuario).
        if ($request->equipo_local !== '') {
            $equipo = Equipo::find($request->equipo_local);
            $partidosEquipoLocal = Partido::where('equipo_local', $equipo->nombre)->get();
            $partidos            = $partidos->intersect($partidosEquipoLocal);
        }

        // Filtro los partidos activos por equipo visitante (si fue ingresado por el usuario).
        if ($request->equipo_visitante !== '') {
            $equipo = Equipo::find($request->equipo_visitante);
            $partidosEquipoVisitante = Partido::where('equipo_visitante', $equipo->nombre)->get();
            $partidos = $partidos->intersect($partidosEquipoVisitante);
        }

        // Busco equipos, torneos y categorias.
        $equipos       = Equipo::where('estado', 1)->get();
        $torneos       = Torneo::where('estado', 1)->get();
        $categorias    = Categoria::all();
        $torneoEquipos = TorneoEquipo::all();

        // Retorno a vista principal de partido con los partidos activos filtrados, lista de equipos activos, lista de torneos activos y lista de categorias.
        return view('partido')
            ->withPartidos($partidos)
            ->withEquipos($equipos)
            ->withTorneos($torneos)
            ->withCategorias($categorias)
            ->with('torneoEquipos', $torneoEquipos);

    }//end searchPartido()


}//end class
