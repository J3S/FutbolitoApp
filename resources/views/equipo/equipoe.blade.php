@extends('layouts.master')

@section('title', 'Editar Equipo')

@section('contentHeaderTitle', 'Editar Equipo')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{!!route('equipo.index')!!}">Equipo</a></li>
    <li class="active">Editar</li>
@endsection

@section('content')
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Equipo</h3>
            </div><!-- /.box-header -->
            <!-- form start envia con AJAX medtodo UPDATE -->
            <form role="form">
                <input type="hidden" name="_token" value="{!! csrf_token() !!}" id="token">
                <input type="hidden" value="{!! $equipo->id !!}" id="idEquipo">
                <div class="box-body">
                    <div class="form-group col-xs-12">
                        <label for="inputNombre">Nombre</label>
                        <input type="text" class="form-control" id="inputNombre" value="{!! $equipo->nombre !!}">
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="inputEntrenador">Entrenador</label>
                        <input type="text" class="form-control" id="inputEntrenador" value="{!! $equipo->director_tecnico !!}">
                    </div>
                    <!-- Select para elegir la categoria del equipo -->
                    <div class="form-group col-xs-12">
                        <label for="inputCategoria">Categoria</label>
                        <select class="form-control" id="inputCategoriaSelect" value="{!! $equipo->categoria !!}">
                            @if(count($categorias) != 0)
                                @foreach($categorias as $categoria)
                                    <option value="{!! $categoria->nombre !!}">{{ $categoria->nombre }}</option>
                                @endforeach
                            @else
                                <option>No se ha registrado ninguna categor&iacute;a</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="inputCategoria">Jugadores</label>
                    </div>
                    <!-- Lista de jugadores seleccionados -->
                    <div class="form-group col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Jugadores </h3>
                            </div>
                            <div class="panel-body">
                                <ul class="list-group"  id="JugadoresElegidos">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- Lista de jugadores para elegir -->
                    <div class="form-group col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Jugadores Disponibles</h3>
                            </div>
                            <div class="panel-body">
                                <ul class="list-group"  id="inputJugadores">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="file">Logo</label>
                        <input type="file" name="file" id="inputFile" class="btn btn-default" />
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <div class="col-xs-2"></div>
                        <div class="col-xs-8">
                            <div class="col-xs-4">
                                <button id="idlimp" type="submit" class="btn btn-primary">Limpiar</button>
                            </div>
                            <div class="col-xs-4 pull-right">
                                <button type="button" id="btn_actualizar" class="btn btn-success">Actualizar</button>
                            </div>
                        </div>
                    <div class="col-xs-2"></div>
                </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <div class="col-xs-2"></div>
@endsection

@section('scriptsPersonales')
    <script src="{!! asset('js/script-equipo.js') !!}" charset="utf-8"></script>
@endsection
