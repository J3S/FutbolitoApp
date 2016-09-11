<?php
/**
 * Controlador para los equipos
 * PHP version 5
 *
 * @category   PHP_6.5
 * @package    App\Http
 * @subpackage Controller
 * @author     Branny Chito <brajchit@espol.edu.ec>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Equipo;
use App\Partido;
use App\Categoria;
use App\Jugador;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

/**
 * EquipoController Class Doc Comment
 *
 * @category   PHP
 * @package    Laravel
 * @subpackage Controller
 * @author     Branny Chito <brajchit@espol.edu.ec>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
class EquipoController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el formulario para buscar 'Equipo'.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();
        return view('equipo.search')->with(compact('categorias'));
    }//end index()


    /**
     * Filter 'Equipo' by nombre y/o categoria.
     *
     * @param \Illuminate\Http\Request $request equipo
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $equipos    = Equipo::where('estado', '1')
                         ->orderBy('Categoria', 'ASC')
                         ->orderBy('nombre', 'ASC')
                         ->get();
        $categorias = Categoria::all();

        // Filtro los equipos por nombre (si es que el filtro fue ingresado)!
        if ($request->nombEquipo !== "") {
            $equiposNombre = Equipo::where(
                'nombre',
                'like',
                '%'.$request->nombEquipo.'%'
            )->get();
            $equipos       = $equipos->intersect($equiposNombre);
        }

        // Filtro los equipos por categoria (si el filtro fue ingresado )!
        if ($request->categoria !== "") {
            $categoria        = Categoria::find($request->categoria);
            $equiposCategoria = Equipo::where(
                'categoria',
                $categoria->nombre
            )->get();
            $equipos          = $equipos->intersect($equiposCategoria);
        }

        return view('equipo.equipo-index')->with(compact('equipos', 'categorias'));

    }//end search()


    /**
     * Show the form for creating a new 'Equipo'.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();

        return view('equipo.equipoc')->with('categorias', $categorias);

    }//end create()


    /**
     * Store a newly created 'Equipo' in storage.
     *
     * @param \Illuminate\Http\Request $request equipo
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate(
            $request,
            array(
             'nombre'     => 'required|unique:equipos,nombre,NULL,id,estado,1',
             'entrenador' => 'alpha_spaces',
             'categoria'  => 'required|exists:categorias,nombre',
            )
        );

        if (count($request->ids) > 0) {
            foreach ($request->ids as $value) {
                $jugadorDB = Jugador::find($value);
                // Id del jugador del request existe en la BDD?
                if ($jugadorDB === null) {
                    $mensaje = array("Jugador(s) no identificado(s)");
                    return response()->json(['ids' => $mensaje], 422);
                } else if ($jugadorDB->id_equipo !== null) {
                    $mensaje = array("Jugador(es) ya pertenece a un equipo.");
                    return response()->json(['ids' => $mensaje], 422);
                }
            }
        }

        $equipo         = new Equipo();
        $equipo->nombre    = $request->nombre;
        $equipo->categoria = $request->categoria;
        if ($request->entrenador != "") {
            $equipo->director_tecnico = $request->entrenador;
        } else {
            $equipo->director_tecnico = null;
        }
        if ($equipo->save() === true) {
            if (count($request->ids) > 0) {
                // Set id_equipo a jugadors selecionados en el nuevo equipo!
                Jugador::whereIn('id', $request->ids)
                       ->update(['id_equipo' => $equipo->id]);
                // Set categoria a jugadors selecionados en el nuevo equipo!
                Jugador::whereIn('id', $request->ids)
                       ->whereNull('categoria')
                       ->update(['categoria' => $equipo->categoria]);
            }
            flash()->info('Equipo ha sido creado con éxito.');
            return response()->json(
                [
                 "mensaje"  => "guardado con exito",
                 "create"   => true,
                 "idEquipo" => $equipo->id,
                ]
            );
        } else {
            return response()->json(["mensaje" => "Error al guardar en la BDD", "create" => false]);
        }//end if

    }//end store()


    /**
     * Display the specified 'Equipo'.
     *
     * @param Class $request equipo
     * @param int   $id      equipo
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $equipo   = Equipo::findOrFail($id);
        $jugadors = Jugador::where('id_equipo', $id)
                           ->get(
                               [
                                'id',
                                'nombres',
                                'apellidos',
                                'categoria',
                                'identificacion',
                                'num_camiseta',
                               ]
                           );
        if ($request->ajax() === true) {
            $jugsArray = $jugadors->toArray();
            return response()->json($jugsArray);
        }
        return view('equipo.equiposhow')->with(compact('equipo', 'jugadors'));

    }//end show()


    /**
     * Response the specifieds 'Jugadores'.
     *
     * @param string $categoria de jugador
     *
     * @return \Illuminate\Http\Response Json
     */
    public function getJugadoresCategoria( $categoria )
    {
        if (Categoria::where('nombre', $categoria)->exists() === false) {
            // Retorna string instead JSON!
            return "categoria no existe";
        }

        $jugadoresCategoria = Jugador::where('categoria', $categoria)
                                     ->whereNull('id_equipo')
                                     ->orWhere(function ($query){
                                        $query->where('categoria', null)
                                              ->whereNull('id_equipo');
                                     })
                                     ->orWhere(function ($query){
                                        $query->where('categoria', "")
                                              ->whereNull('id_equipo');
                                     })
                                     ->get(
                                         [
                                          'id',
                                          'nombres',
                                          'categoria',
                                          'apellidos',
                                         ]
                                     );
        $arrJugs = $jugadoresCategoria->toArray();
        // Ya no hay jugadores disponibles en la DB?
        if (empty($arrJugs) === false) {
            return response()->json($arrJugs);
        } else {
            // Retorna string instead JSON!
            return "No hay jugadores disponibles para esta categoria";
        }

    }//end getJugadoresCategoria()


    /**
     * Show the form for editing the specified 'Equipo'.
     *
     * @param int $id de equipo
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $equipoNew = Equipo::find($id);
        if ($equipoNew === null || $equipoNew->estado === 0) {
            return redirect()->route('equipo.index')->withErrors(
                'El equipo que desea modificar no se encuentra registrado.'
            );
        }

        $jugadors   = Jugador::where('estado', 1)
                            ->where('id_equipo', $id)
                            ->get(
                                [
                                 'id',
                                 'nombres',
                                 'apellidos',
                                 'num_camiseta',
                                 'categoria',
                                ]
                            );
        $categorias = Categoria::all();
        $equipo     = Equipo::find($id);
        return view('equipo.equipoe')->with('equipo', $equipo)
                                     ->with('categorias', $categorias)
                                     ->with('jugadors', $jugadors);
    }//end edit()


    /**
     * Update the specified 'Equipo' in storage.
     *
     * @param \Illuminate\Http\Request $request informacion del form de crear equipo
     * @param int                      $id      de equipo
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $equipoNew = Equipo::find($id);
        // dd($request->all(), $id, $equipoNew);

        $this->validate(
            $request,
            array(
             'nombre'     => 'required|unique:equipos,nombre,'.$equipoNew->id.',id,estado,1',
             'entrenador' => 'alpha_spaces',
             'categoria'  => 'required|exists:categorias,nombre',
             'id'         => 'exists:equipos,id',
            )
        );

        if (count($request->ids) > 0) {
            foreach ($request->ids as $value) {
                $jugadorDB = Jugador::find($value);
                // Id del jugador del request existe en la BDD?
                if ($jugadorDB === null) {
                    $mensaje = array("Jugador(s) no identificado(s)");
                    return response()->json(['ids' => $mensaje], 422);
                } else if ($jugadorDB->id_equipo !== null && $jugadorDB->id_equipo !== $equipoNew->id) {
                    $mensaje = array("Jugador(es) ya pertenece a un equipo.");
                    return response()->json(['ids' => $mensaje], 422);
                }
            }
        }

        // Update Equipos in partidos
        $mensaje = array("Error al actualizar partidos.");
        if (Partido::where('equipo_local', $equipoNew->nombre)->update(['equipo_local' => $request->nombre]) >= 0) {
            //code
        }else {
            return response()->json(['ids' => $mensaje], 422);
        }
        // Update Equipos in partidos
        if (Partido::where('equipo_visitante', $equipoNew->nombre)->update(['equipo_visitante' => $request->nombre]) >= 0) {
            //code
        }else {
            return response()->json(['ids' => $mensaje], 422);
        }

        $equipoNew->nombre    = $request->nombre;
        $equipoNew->categoria = $request->categoria;
        if ($request->entrenador != "") {
            $equipoNew->director_tecnico = $request->entrenador;
        } else {
            $equipoNew->director_tecnico = null;
        }

        if ($equipoNew->save() === true) {
            Jugador::where('id_equipo', $equipoNew->id)
                   ->update(['id_equipo' => null, 'categoria' => null]);

            // Set id_equipo a nuevos jugadors selecionados en el equipo!
            Jugador::whereIn('id', $request->ids)
                   ->update(['id_equipo' => $equipoNew->id]);
            // Set categoria a nuevos jugadors selecionados en el equipo!
            Jugador::whereIn('id', $request->ids)
                   ->whereNull('categoria')
                   ->update(['categoria' => $equipoNew->categoria]);
            flash()->info('Equipo ha sido modificado con éxito.');
            return response()->json(
                [
                 "mensaje"  => "actualizado con exito",
                 "idEquipo" => $equipoNew->id,
                ]
            );
        } else {
            return response()->json(["mensaje" => "Error al actualizar en la BDD"]);
        }//end if

    }//end update()


    /**
     * Remove the specified 'Equipo' from storage.
     *
     * @param int $id de equipo
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipoNew = Equipo::find($id);
        if ($equipoNew === null || $equipoNew->estado === 0) {
            return redirect()->route('equipo.index')->withErrors(
                'El equipo que desea desactivar no se encuentra registrado.'
            );
        }

        try {
            $equipo         = Equipo::find($id);
            $equipo->estado = 0;
            $equipo->save();
            flash()->info('Equipo ha sido borrado con éxito.');
            return redirect()->route('equipo.index');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(
                'El equipo que desea desactivar no se encuentra registrado.'
            );
        }

    }//end destroy()


}//end class
