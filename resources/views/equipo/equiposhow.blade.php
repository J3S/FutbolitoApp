@extends('layouts.master')

@section('title', 'Equipos')

@section('contentHeaderTitle', 'Equipos')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{!!route('equipo.index')!!}">Equipo</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-2"></div>
        <div class="col-xs-12 col-md-8">
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

                    <!-- Lista de jugadores para elegir -->
                    <div class="form-group col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Lista de Jugadores</h3>
                            </div>
                            <div class="panel-body">
                                <ul class="list-group"  id="inputJugadores">
                                    <li class="list-group-item" style="text-align:center">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                Nombres
                                            </div>
                                            <div class="col-xs-4">
                                                Apellidos
                                            </div>
                                            <div class="col-xs-4">
                                                Num Camiseta
                                            </div>
                                        </div>
                                    </li>
                                    @if(count($jugadors) != 0)
                                        @foreach($jugadors as $jugador)
                                            <li class="list-group-item" style="text-align:center">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        {{ $jugador->nombres }}
                                                    </div>
                                                    <div class="col-xs-4">
                                                        {{ $jugador->apellidos }}
                                                    </div>
                                                    <div class="col-xs-4">
                                                        {{ $jugador->num_camiseta }}
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="list-group-item">No se ha registrado ningun jugador para este equipo</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-5">
                        <label for="inputFile">Logo</label>
                    </div>
                    <div class="form-group col-xs-12 col-sm-7">
                        <i class="fa fa fa-futbol-o fa-3x"></i>
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
                                <form style="display:inline-block" action="{!!route('equipo.destroy', ['equipo' => $equipo->id])!!}" method="POST">
                                    <input name="_method" type="hidden" value="DELETE">
                                    {{ csrf_field() }}
                                    <button class="btn btn-danger btn-sm" type="submit" ><i class="fa fa-times fa-lg"> Desactivar</i></button>
                                </form>
                            </div>
                        </div>
                    <div class="col-xs-2"></div>
                </div>
            </div><!-- /.box -->
        </div><!--/.col (left) -->
        <div class="col-xs-12 col-md-2"></div>
    </div>
@endsection
