<?php
/**
 * Controlador para los equipos
 *
 * @category   PHP_6.5
 * @package    Laravel
 * @subpackage Controller
 * @author     Branny Chito <brajchit@espol.edu.ec>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipo;
use App\Categoria;
use App\Jugador;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;

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
             'nombre'    => 'required|unique:equipos,nombre',
             'categoria' => 'required|exists:categorias,nombre',
            )
        );

        if (count($request->ids) < 5) {
            $mensaje = array("Cantida de jugadores insuficiente: ".count($request->ids));
            return response()->json(['ids' => $mensaje], 422);
        } else {
            foreach ($request->ids as $value) {
                $jugadorDB = Jugador::find($value);
                // id del jugador del request existe en la BDD?
                if ( $jugadorDB === null) {
                    $mensaje = array("Jugador(s) no identificado(s)");
                    return response()->json(['ids' => $mensaje], 422);
                } else if ($jugadorDB->id_equipo !== NULL) {
                    $mensaje = array("Jugador ".$jugadorDB->nombres."ya pertenece a un equipo");
                    return response()->json(['ids' => $mensaje], 422);
                }
            }
        }

        $equipo         = new Equipo();
        $equipo->nombre = $request->nombre;
        $equipo->director_tecnico = $request->entrenador;
        $equipo->categoria        = $request->categoria;
        if ($equipo->save() === true) {
            return response()->json(
                [
                 "mensaje"  => "guardado con exito",
                 "idEquipo" => $equipo->id,
                ]
            );
        }

        foreach ($request->ids as $value) {
            $jugadorEquipo            = Jugador::find($value);
            $jugadorEquipo->id_equipo = $equipo->id;
        }

    }//end store()


    /**
     * Display the specified 'Equipo'.
     *
     * @param int $id equipo
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $equipo   = Equipo::findOrFail($id);
        $jugadors = Jugador::where('id_equipo', $id)->get(['nombres', 'apellidos', 'identificacion', 'num_camiseta']);
        return view('equipo.equiposhow')->with(compact('equipo', 'jugadors'));

    }//end show()


    /**
     * Response the specifieds 'Jugadores'.
     *
     * @param string $categoria de jugador
     *
     * @return \Illuminate\Http\Response Json
     */
    public function getJugadoresCategoria($categoria)
    {
        $jugadoresCategoria = Jugador::where('categoria', $categoria)
                                     ->where('id_equipo', null)
                                     ->get(
                                         [
                                          'id',
                                          'nombres',
                                          'categoria',
                                          'apellidos',
                                         ]
                                     );
        $arr = $jugadoresCategoria->toArray();
        if (empty($jugadoresCategoria->toArray()) === false) {
            return response()->json(
                $jugadoresCategoria->toArray()
            );
        } else {
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
        $jugadors  = Jugador::where('estado', 1)
                            ->where('id_equipo', $id)
                            ->get(['id', 'nombres', 'apellidos', 'num_camiseta', 'categoria']);
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
        $this->validate(
            $request,
            array(
             'nombre'    => 'required|unique:equipos,nombre',
             'categoria' => 'required|exists:categorias,nombre',
            )
        );
        if (count($request->ids) < 5) {
            $mensaje = array("Cantida de jugadores insuficiente: ".count($request->ids));
            return response()->json(['ids' => $mensaje], 422);
        } else {
            $mensaje1 = array("Jugador jugador no identificado");
            foreach ($request->ids as $value) {
                if (Jugador::find($value) === null) {
                    return response()->json(['ids' => $mensaje], 422);
                } else if (Jugador::find($value)->estado === 1) {
                    $mensaje = array("Jugador jugador ya pertenece a un equipo");
                    return response()->json(['ids' => $mensaje], 422);
                }
            }
        }

        $equipoNew         = Equipo::find($id);
        $equipoNew->nombre = $request->nombre;
        $equipoNew->director_tecnico = $request->entrenador;
        $equipoNew->categoria        = $request->categoria;
        if ($equipoNew->save() === true) {
            return response()->json(
                [
                 "mensaje"  => "actualizado con exito",
                 "idEquipo" => $equipoNew->id,
                ]
            );
            return "save error";
        }

        foreach ($request->ids as $value) {
            $jugadorEquipo            = Jugador::find($value);
            $jugadorEquipo->id_equipo = $equipo->id;
        }

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
        $equipo         = Equipo::find($id);
        $equipo->estado = 0;
        $equipo->save();
        flash()->info('Equipo borrado con éxito');
        return redirect('equipo');

    }//end destroy()


}//end class
