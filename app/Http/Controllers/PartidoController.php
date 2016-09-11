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
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\PartidoRequest;
use App\Torneo;
use App\TorneoEquipo;
use App\Equipo;
use App\Partido;
use App\Categoria;
use Carbon\Carbon;
use Flash;

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
     * Auth
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }//end __construct()


    /**
     * Prepara la vista principal de la opción partido.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias    = Categoria::all();
        $equipos       = Equipo::where('estado', 1)->get();
        $torneos       = Torneo::where('estado', 1)->orderBy('anio', 'desc')->get();
        $torneoEquipos = TorneoEquipo::all();

        /*
            * Retorno a la vista principal Partido con los siguientes parámetros: listas de categorias, equipos y
            * torneos presentes en la base de datos.
        */

        return view('partido.index')->withCategorias($categorias)
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
        $torneos       = Torneo::where('estado', 1)->orderBy('anio', 'desc')->get();
        $equipos       = Equipo::where('estado', 1)->get();
        $torneoEquipos = TorneoEquipo::all();

        /*
            * Retorno la vista para crear partido con la lista de torneos, equipos y categorias
        */

        return view('partido.create')->withTorneos($torneos)
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
    public function store(PartidoRequest $request)
    {

        // Verificación si el torneo recibido está registrado.
        try {
            $torneoID = Torneo::find($request->torneo)->id;
        } catch (\Exception $e) {
            return redirect()->route('partido.create')->withInput()->withErrors(
                'El torneo seleccionado no se encuentra registrado.'
            );
        }

        // Verificación si el equipo local recibido está registrado y pertenece a la categoria del torneo.
        try {
            $torneo            = Torneo::find($request->torneo);
            $categoria         = Categoria::find($torneo->id_categoria);
            $equipoLocal       = Equipo::where('categoria', $categoria->nombre)->where('id', $request->equipo_local)->first();
            $equipoLocalNombre = $equipoLocal->nombre;
        } catch (\Exception $e) {
            return redirect()->route('partido.create')->withInput()->withErrors(
                'El equipo local seleccionado no se encuentra registrado en el torneo seleccionado.'
            );
        }

        // Verificación si el equipo visitante recibido está registrado y pertenece a la categoria del torneo.
        try {
            $equipoVisitante       = Equipo::where('categoria', $categoria->nombre)->where('id', $request->equipo_visitante)->first();
            $equipoVisitanteNombre = $equipoVisitante->nombre;
        } catch (\Exception $e) {
            return redirect()->route('partido.create')->withInput()->withErrors(
                'El equipo visitante seleccionado no se encuentra registrado en el torneo seleccionado.'
            );
        }

        // Verificación si el equipo local y visitante son iguales.
        if ($request->equipo_local === $request->equipo_visitante) {
            return redirect()->route('partido.create')->withInput()->withErrors(
                'Equipo local y equipo visitante no pueden ser iguales.'
            );
        }

        // Verificación si fecha del partido está en el rango adecuado.
        // Rango: (año del torneo)-enero-1 00:00:00 hasta (año del torneo)-diciembre-31 23:59:59.

        if($request->fecha == ""){
            $request->fecha = null;
        }

        if($request->lugar == ""){
            $request->lugar = null;
        }

        if($request->fecha != null){
            $fechaPartido        = new Carbon(date("Y-m-d H:i:s", strtotime($request->fecha)));
            $fechaInicialPartido = Carbon::create($torneo->anio, 1, 1, 0, 0, 0);
            $fechaFinalPartido   = Carbon::create($torneo->anio, 12, 31, 23, 59, 59);

            if ($fechaPartido < $fechaInicialPartido || $fechaPartido > $fechaFinalPartido) {
                return redirect()->route('partido.create')->withInput()->withErrors(
                    'Fecha inválida. La fecha del partido debe coincidir con el año del torneo seleccionado.'
                );
            }
        }
        

        // Verificación si lugar, fecha y hora del partido no genera colisión de horarios.
        // Existe colisión si existen partidos que se juegan hasta 59 minutos antes o después en la misma cancha.
        if($request->lugar != null && $request->fecha != null){
            $fechaColisionAdelante = (new Carbon(date("Y-m-d H:i:s", strtotime($request->fecha))))->addMinutes(59);
            $fechaColisionAtras    = (new Carbon(date("Y-m-d H:i:s", strtotime($request->fecha))))->subMinutes(59);
            $partidosColision      = Partido::where('estado', '>', 0)->whereBetween('fecha', array($fechaColisionAtras, $fechaColisionAdelante))->where('lugar', $request->lugar)->get();
            $errorColisiones       = [];

            if (count($partidosColision) > 0) {
                foreach ($partidosColision as $partidoColision) {
                    $errorColision = "Colisión de horarios: ".$partidoColision->equipo_local." y ".$partidoColision->equipo_visitante." juegan a las ".$partidoColision->fecha." en la cancha: ".$partidoColision->lugar;
                    array_push($errorColisiones, $errorColision);
                }

                array_push($errorColisiones, "Debe existir por lo menos 1 hora de separación entre partidos en la misma cancha");
                return redirect()->route('partido.create')->withInput()->withErrors($errorColisiones);
            }
        }
        

        // Creo nueva instancia de partido y le asigno todos los valores ingresados por el usuario desde la vista 'partidoc'.
        $partido            = new Partido();
        $partido->lugar     = $request->lugar;
        $partido->fecha     = $request->fecha;
        $partido->id_torneo = $torneoID;
        $partido->jornada   = $request->jornada;
        $partido->arbitro   = $request->arbitro;
        $partido->observacion      = $request->observaciones;
        $partido->gol_local        = $request->gol_local;
        $partido->gol_visitante    = $request->gol_visitante;
        $partido->equipo_local     = $equipoLocalNombre;
        $partido->equipo_visitante = $equipoVisitanteNombre;
        $partido->estado           = $request->estado;

        // Guardo el partido creado en la base de datos.
        $partido->save();
        flash()->info('Partido ha sido creado con éxito.');

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

    /*
        Public function show($id)

        {

        }//end show()
    */


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

        // Verifico si existe algun partido con ese ID.
        if (count($partido) === 0) {
            flash()->error('El partido que desea modificar no se encuentra registrado.');
            return redirect()->route('partido.index');
        }

        // Preparo los datos que seran enviados a la vista.
        $date          = date("Y-m-d\TH:i:s", strtotime($partido->fecha));
        $torneos       = Torneo::where('estado', 1)->orderBy('anio', 'desc')->get();
        $torneo        = Torneo::find($partido->id_torneo);
        $categoria     = Categoria::find($torneo->id_categoria);
        $categorias    = Categoria::all();
        $equipos       = Equipo::where('estado', 1)->get(['id', 'nombre']);
        $equipo        = Equipo::where('nombre', $partido->equipo_local)->get();
        $equipoV       = Equipo::where('nombre', $partido->equipo_visitante)->get();
        $torneoEquipos = TorneoEquipo::all();

        // Retorno vista para editar partido con la información del partido seleccionado, lista de equipos, lista de torneos y lista de categorias.
        return view('partido.edit')->withEquipo($equipo)
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
    public function update(PartidoRequest $request, $id)
    {

        // Verificación si el torneo recibido está registrado.
        try {
            $torneoID = Torneo::find($request->torneo)->id;
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(
                'El torneo seleccionado no se encuentra registrado.'
            );
        }

        // Verificación si el equipo local recibido está registrado y pertenece a la categoria del torneo.
        try {
            $torneo            = Torneo::find($request->torneo);
            $categoria         = Categoria::find($torneo->id_categoria);
            $equipoLocal       = Equipo::where('categoria', $categoria->nombre)->where('id', $request->equipo_local)->first();
            $equipoLocalNombre = $equipoLocal->nombre;
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(
                'El equipo local seleccionado no se encuentra registrado en el torneo seleccionado.'
            );
        }

        // Verificación si el equipo visitante recibido está registrado y pertenece a la categoria del torneo.
        try {
            $equipoVisitante       = Equipo::where('categoria', $categoria->nombre)->where('id', $request->equipo_visitante)->first();
            $equipoVisitanteNombre = $equipoVisitante->nombre;
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(
                'El equipo visitante seleccionado no se encuentra registrado en el torneo seleccionado.'
            );
        }

        // Verificación si el equipo local y visitante son iguales.
        if ($request->equipo_local === $request->equipo_visitante) {
            return back()->withInput()->withErrors(
                'Equipo local y equipo visitante no pueden ser iguales.'
            );
        }

        // Verificación si fecha del partido está en el rango adecuado.
        // Rango: (año del torneo)-enero-1 00:00:00 hasta (año del torneo)-diciembre-31 23:59:59.
        if($request->fecha == ""){
            $request->fecha = null;
        }

        if($request->fecha != null){
            $fechaPartido        = new Carbon(date("Y-m-d H:i:s", strtotime($request->fecha)));
            $fechaInicialPartido = Carbon::create($torneo->anio, 1, 1, 0, 0, 0);
            $fechaFinalPartido   = Carbon::create($torneo->anio, 12, 31, 23, 59, 59);

            if ($fechaPartido < $fechaInicialPartido || $fechaPartido > $fechaFinalPartido) {
                return back()->withInput()->withErrors(
                    'Fecha inválida. La fecha del partido debe coincidir con el año del torneo seleccionado.'
                );
            }
        }

        // Verificación si lugar, fecha y hora del partido no genera colisión de horarios.
        // Existe colisión si existen partidos que se juegan hasta 59 minutos antes o después en la misma cancha.
        $fechaColisionAdelante = (new Carbon(date("Y-m-d H:i:s", strtotime($request->fecha))))->addMinutes(59);
        $fechaColisionAtras    = (new Carbon(date("Y-m-d H:i:s", strtotime($request->fecha))))->subMinutes(59);
        $partidosColision      = Partido::where('estado', '>', 0)->where("id", "!=", $id)->whereBetween('fecha', array($fechaColisionAtras, $fechaColisionAdelante))->where('lugar', $request->lugar)->get();
        $errorColisiones       = [];

        if (count($partidosColision) > 0) {
            foreach ($partidosColision as $partidoColision) {
                $errorColision = "Colisión de horarios: ".$partidoColision->equipo_local." y ".$partidoColision->equipo_visitante." juegan a las ".$partidoColision->fecha." en la cancha: ".$partidoColision->lugar;
                array_push($errorColisiones, $errorColision);
            }

            array_push($errorColisiones, "Debe existir por lo menos 1 hora de separación entre partidos en la misma cancha");
            return back()->withInput()->withErrors($errorColisiones);
        }

        // Encuentro el partido seleccionado por el usuario y modifico todos sus valores por los valores ingresados por el usuario desde la vista 'partidoe'.
        $partido            = Partido::find($id);
        $partido->lugar     = $request->lugar;
        $partido->fecha     = $request->fecha;
        $partido->id_torneo = $torneoID;
        $partido->arbitro   = $request->arbitro;
        $partido->observacion      = $request->observaciones;
        $partido->jornada          = $request->jornada;
        $partido->gol_local        = $request->gol_local;
        $partido->gol_visitante    = $request->gol_visitante;
        $partido->equipo_local     = $equipoLocalNombre;
        $partido->equipo_visitante = $equipoVisitanteNombre;
        $partido->estado           = $request->estado;

        // Actualizo la información del partido en la base de datos.
        $partido->save();
        flash()->info('Partido ha sido modificado con éxito.');

        // Retorno a la vista principal de la opción partido.
        return redirect('partido');

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
        try {
            $partido         = Partido::find($id);
            $partido->estado = 0;
            $partido->save();
            flash()->info('Partido ha sido borrado con éxito.');
            return redirect('partido');
        } catch (\Exception $e) {
            flash()->error('El partido que desea borrar no se encuentra registrado.');
            return redirect()->route('partido.index');
        }

    }//end destroy()

    public function getPartidosByFecha($ini, $fin, $partidos){
        if ($ini !== '' && $fin !== '') {
            $partidosFecha = Partido::whereBetween('fecha', array($ini, $fin))
            ->where('estado', '>', 0)->get();
            $partidos      = $partidos->intersect($partidosFecha);
        }
        return $partidos;
    }

    public function getPartidosByCategoriaYAnio($anio, $categoriaReq, $partidos){
        if ($categoriaReq !== '' && $anio !== '') {
            $categoria = Categoria::where('nombre', $categoriaReq)->first();
            try {
                $torneo         = Torneo::where('id_categoria', $categoria->id)->where('anio', $anio)->first();
                $partidosTorneo = Partido::where('id_torneo', $torneo->id)->get();
                $partidos       = $partidos->intersect($partidosTorneo);
            } catch (\Exception $e) {
                $partidos = $partidos->intersect([]);
            }
        }
        return $partidos;
    }

    public function getPartidosByCategoria($anio, $categoriaReq, $partidos){
       if ($categoriaReq !== '' && $anio === '') {
            $partidosCategoria = [];
            $categoria         = Categoria::where('nombre', $categoriaReq)->first();
            $torneosCategoria  = Torneo::where('id_categoria', $categoria->id)->get();
            foreach ($torneosCategoria as $torneoCategoria) {
                foreach ($partidos as $partidoCategoria) {
                    if ($partidoCategoria->id_torneo === $torneoCategoria->id) {
                        array_push($partidosCategoria, $partidoCategoria);
                    }
                }
            }
            $partidos = $partidos->intersect($partidosCategoria);
        }
        return $partidos;
    }

    public function getPartidosByAnio($anio, $categoriaReq, $partidos){
        if ($categoriaReq === '' && $anio !== '') {
            $partidosAnio = [];
            $torneosAnio  = Torneo::where('anio', $anio)->get();
            foreach ($torneosAnio as $torneoAnio) {
                foreach ($partidos as $partidoAnio) {
                    if ($partidoAnio->id_torneo === $torneoAnio->id) {
                        array_push($partidosAnio, $partidoAnio);
                    }
                }
            }

            $partidos = $partidos->intersect($partidosAnio);
        }
        return $partidos;
    }

    public function getPartidosByJornada($jornada, $partidos){
        if ($jornada !== '') {
            $partidosJornada = Partido::where('jornada', $jornada)->get();
            $partidos        = $partidos->intersect($partidosJornada);
        }
        return $partidos;
    }

    public function getPartidosByEquipoLocal($equipo_local, $partidos){
        if ($equipo_local !== '') {
            $equipo = Equipo::find($equipo_local);
            $partidosEquipoLocal = Partido::where('equipo_local', $equipo->nombre)->get();
            $partidos            = $partidos->intersect($partidosEquipoLocal);
        }   
        return $partidos;   
    }

    public function getPartidosByEquipoVisitante($equipo_visitante, $partidos){
        if ($equipo_visitante !== '') {
            $equipo = Equipo::find($equipo_visitante);
            $partidosEquipoVisitante = Partido::where('equipo_visitante', $equipo->nombre)->get();
            $partidos = $partidos->intersect($partidosEquipoVisitante);
        }
        return $partidos;
    }

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
        // Arreglo que guardara la cantidad de partidos que contiene cada torneo.
        $torneosConPartidos = [];

        // Encuentro todos los partidos activos.
        $partidos = Partido::where('estado', '>', 0)->orderBy('jornada', 'asc')->orderBy('fecha', 'asc')->get();

        // Filtro los partidos activos por fecha inicial y final (si fueron ingresados por el usuario).
        $partidos = $this->getPartidosByFecha($request->ini_partido, $request->fin_partido, $partidos);

        // Filtro los partidos activos por categoria y año del torneo (si fueron ingresados por usuario).
        $partidos = $this->getPartidosByCategoriaYAnio($request->anio, $request->categoria, $partidos);

        // Filtro los partidos activos por categoria del torneo (si fue ingresado por el usuario).
        $partidos = $this->getPartidosByCategoria($request->anio, $request->categoria, $partidos);
 
        // Filtro los partidos activos por año del torneo (si fue ingresado por el usuario).
        $partidos = $this->getPartidosByAnio($request->anio, $request->categoria, $partidos);

        // Filtro los partidos activos por jornada (si fue ingresado por el usuario).
        $partidos = $this->getPartidosByJornada($request->jornada, $partidos);

        // Filtro los partidos activos por equipo local (si fue ingresado por el usuario).
        $partidos = $this->getPartidosByEquipoLocal($request->equipo_local, $partidos);

        // Filtro los partidos activos por equipo visitante (si fue ingresado por el usuario).
        $partidos = $this->getPartidosByEquipoVisitante($request->equipo_visitante, $partidos);

        // Busco equipos, torneos y categorias.
        $equipos       = Equipo::where('estado', 1)->get();
        $torneos       = Torneo::all();
        $categorias    = Categoria::all();
        $torneoEquipos = TorneoEquipo::all();

        // Recorro los torneos, cuento el numero de partidos asignados a cada torneo y los guardo en un arreglo.
        foreach ($torneos as $torneo) {
            $contador = 0;
            foreach ($partidos as $partido) {
                if ($partido->id_torneo === $torneo->id) {
                    $contador++;
                }
            }

            if ($contador !== 0) {
                array_push($torneosConPartidos, $torneo);
            }
        }

        $torneos = $torneos->intersect($torneosConPartidos);

        // Retorno a vista principal de partido con los partidos activos filtrados, lista de equipos activos, lista de torneos activos, lista de categorias y numero de partidos por torneo.
        return view('partido.index')
            ->withPartidos($partidos)
            ->withEquipos($equipos)
            ->withTorneos($torneos)
            ->withCategorias($categorias)
            ->with('torneoEquipos', $torneoEquipos);

    }//end searchPartido()


}//end class
