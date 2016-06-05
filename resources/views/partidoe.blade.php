@extends('layouts.master')

@section('title', 'Editar Partido')

@section('contentHeaderTitle', 'Editar Partido')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('partido') }}">Partido</a></li>
    <li class="active">Editar</li>
@endsection

@section('content')
   <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Partido</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="/partido/{{ $partido->id }}" method="POST">
                <input name="_method" type="hidden" value="PUT">
                {!! csrf_field() !!}
                <div class="box-body">
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="listaTorneo">Torneo</label>
                        <select class="form-control" id="listaTorneo" name="torneo">
                            @foreach($categorias as $categoria)
                                @foreach($torneos as $torneo)
                                    @if($categoria->id == $torneo->id_categoria)
                                        @if($torneo->id == $partido->id_torneo)
                                            <option selected="selected" value="{{ $torneo['id'] }}">{{ $categoria['nombre'] }} {{ $torneo['anio'] }}</option>
                                        @else
                                            <option value="{{ $torneo['id'] }}">{{ $categoria['nombre'] }} {{ $torneo['anio'] }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                     <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputJornada">Jornada #</label>
                            <input type="number" value="{{ $partido['jornada'] }}" min="0" class="form-control" id="inputJornada" name="jornada" placeholder="Ingrese jornada del partido">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="inputArbitro">Arbitro</label>
                        <input type="text" value="{{ $partido['arbitro'] }}" class="form-control" id="inputArbitro" name="arbitro" placeholder="Ingrese arbitro">
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="inputFechaPart">Fecha del partido</label>
                        <input type="datetime-local" value="{{ $date }}" class="form-control" id="inputFechaPart" name="fecha" >
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputLugar">Lugar</label>
                            <input type="text" value="{{ $partido['lugar'] }}" class="form-control" id="inputLugar" name="lugar" placeholder="Ingrese lugar del partido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputObserv">Observaciones</label>
                            <input type="text" value="{{ $partido['observacion'] }}"  class="form-control" id="inputObserv" name="observaciones" placeholder="Ingrese observaciones del partido">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 separador text-center">
                        <label class="header-group" for="listaEquipo">Equipos</label>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="listaEquipo1">Equipo local</label>
                        <select class="form-control" id="listaEquipo1" name="equipo_local">
                            @foreach($equipos as $equipo)
                                @if($equipo->nombre == $partido->equipo_local)
                                    <option selected="selected" value="{{ $equipo['id'] }}">{{ $equipo['nombre'] }}</option>
                                @else
                                    <option value="{{ $equipo['id'] }}">{{ $equipo['nombre'] }}</option>
                                @endif
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="listaEquipo2">Equipo visitante</label>
                        <select class="form-control" id="listaEquipo2" name="equipo_visitante">
                            @foreach($equipos as $equipo)
                                @if($equipo->nombre == $partido->equipo_visitante)
                                    <option selected="selected" value="{{ $equipo['id'] }}">{{ $equipo['nombre'] }}</option>
                                @else
                                    <option value="{{ $equipo['id'] }}">{{ $equipo['nombre'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                   <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputGolLocal">Goles local</label>
                            <input type="number" value="{{ $partido['gol_local'] }}" min="0" class="form-control" id="inputGolLocal" name="gol_local">
                        </div>
                    </div>
                   <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputGolVisitante">Goles visitante</label>
                            <input type="number" value="{{ $partido['gol_visitante'] }}" min="0" class="form-control" id="inputGolVisitante" name="gol_visitante">
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
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>
                </div>
                <div class="col-xs-2"></div>
              </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <div class="col-xs-2"></div>
@endsection