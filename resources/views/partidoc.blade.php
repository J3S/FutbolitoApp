<!-- 
    Vista para crear un partido.
    Contiene el formulario para crear un nuevo partido.
    También muestra todos los equipos disponibles para agregar equipo local
    y visitante dependiendo del torneo seleccionado.
-->

@extends('layouts.master')

@section('title', 'Crear Partido')

@section('contentHeaderTitle', 'Crear Partido')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('partido') }}">Partido</a></li>
    <li class="active">Crear</li>
@endsection

@section('content')
    <div class="col-xs-12">
        <div class="col-xs-2"></div>
        <!-- Alert que muestra todos los errores en los campos si falla al hacer post -->
        <div class="col-xs-8">
            @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="col-xs-2"></div>
    </div>
   <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Crear Partido</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{!!route('partido.store')!!}" method="post">
            {!! csrf_field() !!}
                <div class="box-body">
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="listaAnio">Torneo</label>
                        <select class="form-control" id="listaAnio" name="torneo">
                            @foreach($categorias as $categoria)
                                @foreach($torneos as $torneo)
                                    @if($categoria->id == $torneo->id_categoria)
                                        <option>{{ $categoria['nombre'] }} : {{ $torneo['anio'] }}</option>
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputJornada">Jornada #</label>
                            <input type="number" min="0" class="form-control" id="inputJornada" name="jornada" placeholder="Ingrese jornada del partido">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="inputArbitro">Arbitro</label>
                        <input type="text" class="form-control" id="inputArbitro" name="arbitro" placeholder="Ingrese arbitro">
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="inputFechaPart">Fecha del partido</label>
                        <input type="datetime-local" class="form-control" id="inputFechaPart" name="fecha" placeholder="Ingrese fecha del partido">
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputLugar">Lugar</label>
                            <input type="text" class="form-control" id="inputLugar" name="lugar" placeholder="Ingrese lugar del partido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputObserv">Observaciones</label>
                            <input type="text" class="form-control" id="inputObserv" name="observaciones" placeholder="Ingrese observaciones del partido">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 separador text-center">
                        <label class="header-group" for="listaEquipo">Equipos</label>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="listaEquipo1">Equipo local</label>
                        <select class="form-control" id="listaEquipo1" name="equipo_local">
                            @foreach($equipos as $equipo)
                            <option>{{ $equipo['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="listaEquipo2">Equipo visitante</label>
                        <select class="form-control" id="listaEquipo2" name="equipo_visitante">
                            @foreach($equipos as $equipo)
                            <option>{{ $equipo['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                   <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputGolLocal">Goles local</label>
                            <input type="number" min="0" class="form-control" id="inputGolLocal" name="gol_local" placeholder="Ingrese goles de equipo local">
                        </div>
                    </div>
                   <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputGolVisitante">Goles visitante</label>
                            <input type="number" min="0" class="form-control" id="inputGolVisitante" name="gol_visitante" placeholder="Ingrese goles de equipo visitante">
                        </div>
                    </div>
                </div><!-- /.box-body -->

              <div class="box-footer">
                <div class="col-xs-2"></div>
                <div class="col-xs-8">
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary">Limpiar</button>
                    </div>
                    <div class="col-xs-4 pull-right">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </div>
                <div class="col-xs-2"></div>
              </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <div class="col-xs-2"></div>
@endsection