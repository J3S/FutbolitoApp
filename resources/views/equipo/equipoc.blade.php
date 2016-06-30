@extends('layouts.master')

@section('title', 'Crear Equipo')

@section('contentHeaderTitle', 'Crear Equipo')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{!!route('equipo.index')!!}">Equipo</a></li>
    <li class="active">Crear</li>
    <style media="screen">
    .list-group-item {
        /*padding: 5px 15px;*/
        }
    </style>
@endsection

@section('content')
    <div class="col-xs-12 col-md-2"></div>
    <div class="col-xs-12 col-md-8">
        <!-- general form elements -->
        <div class="box box-primary">
            <!-- mensajes de errro de validacion -->
            <div class="alert alert-danger" style="display: none">
                <ul id="alerts">
                </ul>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">Crear Equipo</h3>
            </div><!-- /.box-header -->

            <!-- form start -->
            <form role="form">
                <input type="hidden" name="_token" value="{!! csrf_token() !!}" id="token">
                <div class="box-body">
                    <div class="form-group col-xs-12">
                        <label for="inputNombre">Nombre</label>
                        <input type="text" class="form-control" id="inputNombre" placeholder="Ingrese nombre equipo">
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="inputEntrenador">Entrenador</label>
                        <input type="text" class="form-control" id="inputEntrenador" placeholder="Ingrese nombre entrenador">
                    </div>
                    <!-- Select para elegir la categoria del equipo -->
                    <div class="form-group col-xs-12">
                        <label for="inputCategoria">Categoria</label>
                        <select class="form-control" id="inputCategoriaSelect">
                            <option value="noSelected">-Selecionar-</option>
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
                            <div class="panel-heading" style="text-align:center">
                                <h3 class="panel-title">
                                    <a data-toggle="collapse" href="#collapseListJug" aria-expanded="true" aria-controls="collapseOne">
                                        Jugadores Disponibles <span class="fa fa-chevron-down " > </span>
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseListJug" class="panel-collapse collapse in" role="contenido" aria-labelledby="cabecera de lista">
                                <div class="panel-body">
                                    <ul class="list-group"  id="inputJugadores">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label id="logo" for="file">Logo</label>
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
                                <button type="button" id="btn_guardar" class="btn btn-success">Guardar</button>
                            </div>
                        </div>
                    <div class="col-xs-2"></div>
                </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <div class="col-xs-12 col-md-2"></div>
@endsection

@section('scriptsPersonales')
    <script src="{!! asset('js/script-equipo.js') !!}" charset="utf-8"></script>
@endsection
