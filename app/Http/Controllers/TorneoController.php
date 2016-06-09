<?php
/**
 * Controlador para los torneos
 *
 * @category   PHP
 * @package    Laravel
 * @subpackage Controller
 * @author     Johnny Suárez <johnnysuarez@outlook.com>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Torneo;
use App\Equipo;
use App\Categoria;
use App\TorneoEquipo;

/**
 * TorneoController Class Doc Comment
 *
 * @category   PHP
 * @package    Laravel
 * @subpackage Controller
 * @author     Johnny Suárez <johnnysuarez@outlook.com>
 * @license    MIT, http://opensource.org/licenses/MIT
 * @link       http://definirlink.local
 */
class TorneoController extends Controller
{


    /**
     * Muestra la vista principal de la opción torneo.
     * Devuelve la vista torneo junto con los torneos del
     * año actual.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Variable anioServer        - Año actual del servidor.
        // Variable categorias        - Contiene todas las categorías que están guardadas en la base.
        // Variable torneosExistentes - Contiene todos los torneos existentes guardados en la base.
        // Variable inforTorneo       - Contiene la información de un torneo en particular.
        $anioServer           = date('Y');
        $categorias           = Categoria::all();
        $torneosExistentes    = [];
        $infoTorneo           = [];
        $contadorInexistentes = 0;

        // Recorro el arreglo de categorías y busco el torneo de esa categoría del año actual.
        // Uniendo la información de ese torneo con el nombre de la categoría(si no existe el
        // torneo el id será 0) y colocando esa información en el arreglo con todos los torneos.
        foreach ($categorias as $categoria) {
            $torneo = Torneo::where('id_categoria', $categoria->id)
                            ->where('anio', $anioServer)
                            ->where('estado', 1)
                            ->first();
            if (count($torneo) === 0) {
                ++$contadorInexistentes;
                $infoTorneo = [
                               'id'        => 0,
                               'categoria' => $categoria->nombre,
                              ];
                array_push($torneosExistentes, $infoTorneo);
            } else {
                $infoTorneo = [
                               'id'        => $torneo->id,
                               'categoria' => $categoria->nombre,
                              ];
                array_unshift($torneosExistentes, $infoTorneo);
            }
        }

        // Retorno la vista con todos los torneos del año actual, con el año actual del servidor,
        // el numero de torneos que no se han creado en el año, y todas las categorías.
        return view('torneo')->with('torneos', $torneosExistentes)
                             ->with('anioServer', $anioServer)
                             ->with('inexistentes', $contadorInexistentes)
                             ->with('categorias', $categorias);

    }//end index()


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
        // Variable categorías         - Contiene todas las categorías que están guardadas en la base.
        // Variable equiposxcategorias - Contiene a los equipos registrados divididos por categorías.
        $categorias         = Categoria::all();
        $equiposxcategorias = [];

        foreach ($categorias as $categoria) {
            $equipos = Equipo::where('categoria', $categoria->nombre)
                             ->get();
            $equiposxcategorias[$categoria->nombre] = $equipos;
        }

        // Retorno la vista con todas las categorías disponibles.
        return view('torneoc')->with('categorias', $categorias)
                              ->with('equiposxcategorias', $equiposxcategorias);

    }//end create()


    /**
     * Crea un nuevo torneo en la base.
     * Se verifica cuantos elementos tiene el $request para identificar
     * si hay equipos a ser enlazados con el torneo que se va a crear.
     * Si existe los equipos se los enlaza con el torneo usando la tabla
     * torneo_equipos.
     *
     * @param \Illuminate\Http\Request $request Información del torneo
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validación de los campos.
        $this->validate(
            $request,
            [
             'categoria' => 'required',
             'anio'      => 'required|numeric',
            ]
        );

        try {
            $categoriaID = Categoria::where('nombre', $request->categoria)
                                    ->get(['id'])
                                    ->toArray()[0]['id'];
        } catch (\Exception $e) {
            return redirect()->route('torneo.create')->withErrors('La categoría que le asignó a este torneo no se encuentra registrada.');
        }

        $torneoExistente = Torneo::where('anio', $request->anio)
                                 ->where('id_categoria', $categoriaID)
                                 ->where('estado', 1)
                                 ->get();
        if (count($torneoExistente) !== 0) {
            return redirect()->route('torneo.create')->withErrors('Ya existe un torneo creado con ese año y categoría.');
        }

        // Variable contador - Controla que la información recibida sea asignado en el lugar correcto.
        // Variable torneo   - Nuevo torneo que se va a guardar en la base.
        $contador = 0;
        $torneo   = new Torneo();

        foreach ($request->all() as $valor) {
            // Asignación del primer campo del torneo.
            if ($contador === 1) {
                $torneo->anio = $valor;
            }

            // Asignación del segundo campo del torneo y guardar el torneo en la base.
            if ($contador === 2) {
                try {
                    $categoriaID          = Categoria::where('nombre', $valor)
                                                     ->get(['id'])
                                                     ->toArray()[0]['id'];
                    $torneo->id_categoria = $categoriaID;
                    $torneo->estado       = 1;
                    $torneo->save();
                } catch (\Exception $e) {
                    return redirect()->route('torneo.create')->withErrors('La categoría que le asignó a este torneo no se encuentra registrada.');
                }
            }

            // Crear y guardar los registros relacionados con los equipos participantes en el torneo.
            // en la tabla torneo_equipos.
            if ($contador > 2) {
                $torneoEquipo            = new TorneoEquipo();
                $torneoEquipo->id_torneo = $torneo->id;
                try {
                    $equipoID = Equipo::where('nombre', $valor)
                                      ->get(['id'])
                                      ->toArray()[0]['id'];
                    $torneoEquipo->id_equipo = $equipoID;
                    $torneoEquipo->save();
                } catch (\Exception $e) {
                    return redirect()->route('torneo.create')->withErrors('El equipo que desea agregar no se encuentra registrado.');
                }
            }

            ++$contador;
        }//end foreach

        // Redirecciono a la ruta torneo.
        return redirect('torneo');

    }//end store()


    /**
     * Display the specified resource.
     *
     * @param int $id ID del torneo que se desea mostrar
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }//end show()


    /**
     * Muesta el formulario para editar la información del torneo seleccionado($id).
     * También muestra a todos los equipos que han sido agregados a ese torneo
     * dandole la opción de quitarlos del torneo o de agregar un nuevo equipo
     * al torneo.
     *
     * @param int $id ID del torneo que se desea modificar
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Variable categorías         - Contiene todas las categorías que están guardadas en la base.
        // Variable equiposxcategorias - Contiene a los equipos registrados divididos por categorías.
        $categorias         = Categoria::all();
        $equiposxcategorias = [];

        foreach ($categorias as $categoria) {
            $equipos = Equipo::where('categoria', $categoria->nombre)
                             ->get();
            $equiposxcategorias[$categoria->nombre] = $equipos;
        }

        // Variable torneo - Contiene el torneo que se desea modificar.
        // Variable torneoEquipos - Contiene todos los registros de la tabla torneo_equipos que tienen
        // id_torneo = $id.
        $torneo        = Torneo::find($id);
        $torneoEquipos = TorneoEquipo::where('id_torneo', $id)
                                     ->get();
        if (count($torneo) === 0) {
            return redirect()->route('torneo.index')->withErrors('El torneo al que desea modificar no se encuentra registrado.');
        }

        // Búsqueda de los equipos que han sido agregados a ese torneo.
        $equiposAgregados = [];
        foreach ($torneoEquipos as $torneoEquipo) {
            $infoEquipo = Equipo::where('id', $torneoEquipo->id_equipo)
                                ->get();
            array_push($equiposAgregados, $infoEquipo[0]);
        }

        // Retorno la vista con la información del torneo seleccionado, con los equipos divididos
        // por categorías, todas las categorías registradas y con los equipos que están agregados
        // a ese torneo.
        return view('torneoe')->with('torneo', $torneo)
                              ->with('equiposxcategorias', $equiposxcategorias)
                              ->with('categorias', $categorias)
                              ->with('equiposAgregados', $equiposAgregados);

    }//end edit()


    /**
     * Actualiza la información de un torneo específico utilizando el $id.
     *
     * @param \Illuminate\Http\Request $request Información del torneo que se desea actualizar
     * @param int                      $id      ID del torneo que se va a actualizar
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
             'categoria' => 'required',
             'anio'      => 'required|numeric',
            ]
        );

        // Variable contador - Controla que la información recibida sea asignado en el lugar correcto.
        // Variable torneo   - Torneo que se va a modificar.
        $contador = 0;
        $torneo   = Torneo::find($id);

        foreach ($request->all() as $valor) {
            // Asignación del primer campo del torneo.
            if ($contador === 1) {
                $torneo->anio = $valor;
            }

            // Asignación del segundo campo del torneo y guardar el torneo en la base.
            if ($contador === 2) {
                try {
                    $categoriaID          = Categoria::where('nombre', $valor)
                                                    ->get(['id'])
                                                    ->toArray()[0]['id'];
                    $torneo->id_categoria = $categoriaID;
                    $torneo->estado       = 1;
                    $torneo->save();
                } catch (\Exception $e) {
                    return redirect()->route('torneo.edit')->withErrors('La categoría que le asignó a este torneo no se encuentra registrada.');
                }

                // Eliminación de todos los registros relacionados a ese torneo en la tabla torneo_equipos.
                $torneosEquiposEliminados = TorneoEquipo::where('id_torneo', $torneo->id)
                                                        ->delete();
            }

            // Crear y guardar los registros relacionados con los equipos participantes en el torneo
            // en la tabla torneo_equipos.
            if ($contador > 2) {
                try {
                    $torneoEquipo            = new TorneoEquipo();
                    $torneoEquipo->id_torneo = $torneo->id;
                    $equipoID = Equipo::where('nombre', $valor)->get(['id'])->toArray()[0]['id'];
                    $torneoEquipo->id_equipo = $equipoID;
                    $torneoEquipo->save();
                } catch (\Exception $e) {
                    return redirect()->route('torneo.edit').withErrors('El equipo que desea agregar no se encuentra registrado.');
                }
            }

            ++$contador;
        }//end foreach

        // Redirecciono a la ruta torneo.
        return redirect('torneo');

    }//end update()


    /**
     * Cambia el estado a 0, del torneo seleccionado identificado por el $id.
     *
     * @param int $id ID del torneo que se desea desactivar
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $torneo         = Torneo::find($id);
            $torneo->estado = 0;
            $torneo->save();
            return $this->index();
        } catch (\Exception $e) {
            return reditect()->route('torneo.index')->withErrors('El torneo al que desea desactivar no se encuentra registrado.');
        }

    }//end destroy()


}//end class
