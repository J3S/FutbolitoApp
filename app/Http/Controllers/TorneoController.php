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
use App\Partido;
use Flash;

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
        /*
            * Variable anioServer - Año actual del servidor.
            * Variable categorias - Contiene todas las categorías que están
            * guardadas en la base.
            * Variable torneosExistentes - Contiene todos los torneos existentes
            * guardados en la base.
            * Variable inforTorneo - Contiene la información de un torneo en
            * particular.
        */

        $anioServer           = date('Y');
        $categorias           = Categoria::all();
        $torneosExistentes    = [];
        $infoTorneo           = [];
        $contadorInexistentes = 0;

        /*
            * Recorro el arreglo de categorías y busco el torneo de esa categoría
            * del año actual.
            * Uniendo la información de ese torneo con el nombre de la categoría
            * (si no existe el torneo el id será 0) y colocando esa información en
            * el arreglo con todos los torneos.
        */

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

        /*
            * Retorno la vista con todos los torneos del año actual, con el año
            * actual del servidor, el numero de torneos que no se han creado en el
            * año, y todas las categorías.
        */

        return view('torneo.index')->with('torneos', $torneosExistentes)
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
        /*
            * Variable categorías - Contiene todas las categorías que están
            * guardadas en la base.
            * Variable equiposxcategorias - Contiene a los equipos registrados
            * divididos por categorías.
        */

        $categorias         = Categoria::all();
        $equiposxcategorias = [];

        // División de los equipos por categorías.
        foreach ($categorias as $categoria) {
            $equipos = Equipo::where('categoria', $categoria->nombre)
                             ->get();
            $equiposxcategorias[$categoria->nombre] = $equipos;
        }

        // Retorno la vista con todas las categorías disponibles.
        return view('torneo.create')->with('categorias', $categorias)
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

        // Verificación si la categoría recibida está registrada.
        try {
            $categoriaID = Categoria::where('nombre', $request->categoria)
                                    ->get(['id'])
                                    ->toArray()[0]['id'];
        } catch (\Exception $e) {
            return redirect()->route('torneo.create')->withErrors(
                'La categoría asignada a este torneo no se encuentra registrada.'
            );
        }

        // Verificación si existe un torneo con el mismo año y categoría.
        $torneoExistente = Torneo::where('anio', $request->anio)
                                 ->where('id_categoria', $categoriaID)
                                 ->where('estado', 1)
                                 ->get();
        if (count($torneoExistente) !== 0) {
            return redirect()->route('torneo.create')->withErrors(
                'Ya existe un torneo creado con ese año y categoría.'
            );
        }

        /*
            * Variable contador - Controla que se empiece a buscar a los equipos
            * desde la 4ta iteración.
            * Variable equiposAgregadosID - Contiene el ID de todos los equipos que
            * van a ser agregados al torneo.
        */

        $contador           = 0;
        $equiposAgregadosID = [];

        /*
            * Verificación para que los equipos agregados sean de la misma
            * categoría del torneo.
        */

        foreach ($request->all() as $valor) {
            if ($contador > 2) {
                $equipo = Equipo::where('nombre', $valor)
                                ->where('categoria', $request->categoria)
                                ->where('estado', 1)
                                ->first();
                if (count($equipo) === 1) {
                    array_push($equiposAgregadosID, $equipo->id);
                } else {
                    return redirect()->route('torneo.create')->withErrors(
                        'Se trató de agregar a un equipo que no tiene la misma
                        categoría del torneo.'
                    );
                }
            }

            $contador++;
        }

        // Creación del torneo.
        $torneo       = new Torneo();
        $torneo->anio = $request->anio;
        $torneo->id_categoria = $categoriaID;
        $torneo->estado       = 1;
        $torneo->save();
        flash()->info('Torneo ha sido creado con éxito.');

        // Asignación de ese equipo al torneo recién creado.
        foreach ($equiposAgregadosID as $equipoAgregadoID) {
            $torneoEquipo            = new TorneoEquipo();
            $torneoEquipo->id_torneo = $torneo->id;
            $torneoEquipo->id_equipo = $equipoAgregadoID;
            $torneoEquipo->save();
        }

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
        /*
            * Variable categorías - Contiene todas las categorías que están
            * guardadas en la base.
            * Variable equiposxcategorias - Contiene a los equipos registrados
            * divididos por categorías.
        */

        $categorias         = Categoria::all();
        $equiposxcategorias = [];

        // División de los equipos por categorías.
        foreach ($categorias as $categoria) {
            $equipos = Equipo::where('categoria', $categoria->nombre)
                             ->get();
            $equiposxcategorias[$categoria->nombre] = $equipos;
        }

        /*
            * Variable torneo - Contiene el torneo que se desea modificar.
            * Variable torneoEquipos - Contiene todos los registros de la tabla
            * torneo_equipos que tienen id_torneo = $id.
        */

        $torneo        = Torneo::find($id);
        $torneoEquipos = TorneoEquipo::where('id_torneo', $id)
                                     ->get();
        // Verificación de la existencia del torneo.
        if (count($torneo) === 0) {
            return redirect()->route('torneo.index')->withErrors(
                'El torneo al que desea modificar no se encuentra registrado.'
            );
        }

        // Búsqueda de los equipos que han sido agregados a ese torneo.
        $equiposAgregados = [];
        foreach ($torneoEquipos as $torneoEquipo) {
            $infoEquipo = Equipo::where('id', $torneoEquipo->id_equipo)
                                ->get();
            array_push($equiposAgregados, $infoEquipo[0]);
        }

        /*
            * Retorno la vista con la información del torneo seleccionado, con los
            * equipos divididos por categorías, todas las categorías registradas y
            * con los equipos que están agregados a ese torneo.
        */

        return view('torneo.edit')->with('torneo', $torneo)
                                  ->with('equiposxcategorias', $equiposxcategorias)
                                  ->with('categorias', $categorias)
                                  ->with('equiposAgregados', $equiposAgregados);

    }//end edit()


    /**
     * Actualiza la información de un torneo específico utilizando el $id.
     *
     * @param \Illuminate\Http\Request $request Información que se va a actualizar
     * @param int                      $id      ID del torneo que se va a actualizar
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validación de los campos.
        $this->validate(
            $request,
            [
             'categoria' => 'required',
             'anio'      => 'required|numeric',
            ]
        );

        // Verificación si la categoría recibida está registrada.
        try {
            $categoriaID = Categoria::where('nombre', $request->categoria)
                                    ->get(['id'])
                                    ->toArray()[0]['id'];
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(
                'La categoría asignada a este torneo no se encuentra registrada.'
            );
        }

        /*
            * Variable contador - Controla que se empiece a buscar a los equipos
            * desde la 4ta iteración.
            * Variable equiposAgregadosID - Contiene el ID de todos los equipos que
            * van a ser agregados al torneo.
        */

        $contador           = 0;
        $equiposAgregadosID = [];

        /*
            * Verificación para que los equipos agregados sean de la misma
            * categoría del torneo.
        */

        foreach ($request->all() as $valor) {
            if ($contador > 3) {
                $equipo = Equipo::where('nombre', $valor)
                                ->where('categoria', $request->categoria)
                                ->where('estado', 1)
                                ->first();
                if (count($equipo) === 1) {
                    array_push($equiposAgregadosID, $equipo->id);
                } else {
                    return back()->withInput()->withErrors(
                        'Se trató de agregar a un equipo que no tiene la misma
                        categoría del torneo.'
                    );
                }
            }

            $contador++;
        }

        // Verificación si existe un torneo con el mismo año y categoría.
        $torneoExistente = Torneo::where('anio', $request->anio)
                                 ->where('id_categoria', $categoriaID)
                                 ->where('estado', 1)
                                 ->get();

        if (count($torneoExistente) !== 0) {
            if ($torneoExistente[0]['id'] !== intval($id)) {
                return back()->withInput()->withErrors(
                    'Ya existe un torneo creado con ese año y categoría.'
                );
            }
        }

        /*
            * Variable contador - Controla que la información recibida sea asignado
            * en el lugar correcto.
            * Variable torneo - Torneo que se va a modificar.
        */

        $contador     = 0;
        $torneo       = Torneo::find($id);
        $torneo->anio = $request->anio;
        $torneo->id_categoria = $categoriaID;
        $torneo->estado       = 1;
        $torneo->save();
        flash()->info('Torneo ha sido modificado con éxito.');

        /*
            *Eliminación de todos los registros relacionados a ese torneo en la
            tabla torneo_equipos.
        */

        $torneosEquiposEliminados = TorneoEquipo::where('id_torneo', $torneo->id)
                                                ->delete();

        // Asignación de ese equipo al torneo recién creado.
        foreach ($equiposAgregadosID as $equipoAgregadoID) {
            $torneoEquipo            = new TorneoEquipo();
            $torneoEquipo->id_torneo = $torneo->id;
            $torneoEquipo->id_equipo = $equipoAgregadoID;
            $torneoEquipo->save();
        }

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
            flash()->info('Torneo ha sido borrado con éxito.');
            $partidosTorneo = Partido::where('id_torneo', $torneo->id)
                               ->get();
            foreach ($partidosTorneo as $partidoTorneo) {
                $partidoTorneo->estado = 0;
                $partidoTorneo->save();
            }

            return redirect()->back();
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(
                'El torneo al que desea desactivar no se encuentra registrado.'
            );
        }

    }//end destroy()


    /**
     * Busca torneos de acuerdo al año o categoría.
     *
     * @param \Illuminate\Http\Request $request Filtros para buscar.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchTorneo(Request $request)
    {
        // Validación de los campos.
        $this->validate(
            $request,
            ['anio' => 'numeric']
        );

        // Verificación si la categoría recibida está registrada.
        if ($request->categoria !== "") {
            try {
                $categoriaID = Categoria::where('nombre', $request->categoria)
                                        ->get(['id'])
                                        ->toArray()[0]['id'];
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(
                    'La categoría seleccionada no se encuentra registrada.'
                );
            }
        }

        // Búsqueda de los torneos de acuerdo a los datos recibidos.
        $torneosBuscados;
        if ($request->anio !== "" && $request->categoria !== "") {
            $torneosBuscados = Torneo::where('anio', $request->anio)
                                     ->where('id_categoria', $categoriaID)
                                     ->where('estado', 1)
                                     ->orderBy('anio', 'desc')
                                     ->get();
        } else if ($request->anio !== "") {
            $torneosBuscados = Torneo::where('anio', $request->anio)
                                     ->where('estado', 1)
                                     ->orderBy('anio', 'desc')
                                     ->get();
        } else if ($request->categoria !== "") {
            $torneosBuscados = Torneo::where('id_categoria', $categoriaID)
                                     ->where('estado', 1)
                                     ->orderBy('anio', 'desc')
                                     ->get();
        } else {
            $torneosBuscados = [];
        }

        // Datos de los torneos encontrados.
        $torneosEncontrados = [];
        foreach ($torneosBuscados as $torneoBuscado) {
            $categoria = Categoria::where('id', $torneoBuscado['id_categoria'])
                                  ->first();
            array_push(
                $torneosEncontrados,
                [
                 $torneoBuscado['id'],
                 $torneoBuscado['anio'],
                 $categoria->nombre,
                ]
            );
        }

        // Retorno a la vista principal de torneo con los torneos encontrados.
        return redirect()->back()->with('torneosEncontrados', $torneosEncontrados);

    }//end searchTorneo()


}//end class
