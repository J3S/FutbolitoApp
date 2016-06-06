@extends('layouts.master')

@section('title', 'Equipos')

@section('contentHeaderTitle', 'Equipos')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{!!route('equipo.index')!!}">Equipo</a></li>
@endsection

@section('content')
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Perfil de Equipo</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group col-xs-12">
                    <label for="inputNombre">Nombre</label>
                    <li class="list-group-item">{!! $equipo->nombre !!}</li>
                </div>
                <div class="form-group col-xs-12">
                    <label for="inputEntrenador">Entrenador</label>
                    <li class="list-group-item">{!! $equipo->director_tecnico !!}</li>
                </div>
                <div class="form-group col-xs-12">
                    <label for="inputCategoria">Categoria</label>
                    <li class="list-group-item">{!! $equipo->categoria !!}</li>
                </div>
                <div class="form-group col-xs-12">
                    <label for="inputCategoria">Estado</label>
                    <?php if ($equipo->estado): ?>
                        <li class="list-group-item">Activo</li>
                    <?php else: ?>
                        <li class="list-group-item">Desactivado</li>
                    <?php endif; ?>

                </div>
                <!-- Lista de jugadores para elegir -->
                <div class="form-group col-xs-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">Lista de Jugadores</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group"  id="inputJugadores">
                                <li class="list-group-item">No hay jugadores par mostrar</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group col-xs-12 col-sm-5">
                    <label for="inputFile">Logo</label>
                </div>
                <div class="form-group col-xs-12 col-sm-7">
                    <i class="fa fa fa-futbol-o fa-5x"></i>
                </div>
            </div><!-- /.box-body -->

            <div class="box-footer">
                <div class="col-xs-2"></div>
                    <div class="col-xs-8">
                        <div class="col-xs-4">
                            <a class="btn btn-warning" href="{!!route('equipo.edit', ['equipo' => $equipo->id])!!}">
                                <i class="fa fa-pencil fa-lg"></i> Editar
                            </a>
                        </div>
                        <div class="col-xs-4 pull-right">
                            <a class="btn btn-danger" href="{!!route('equipo.destroy', ['equipo' => $equipo->id])!!}">
                                <i class="fa fa-trash-o fa-lg"></i> Desacitivar
                            </a>
                        </div>
                    </div>
                <div class="col-xs-2"></div>
            </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <div class="col-xs-2"></div>
@endsection
