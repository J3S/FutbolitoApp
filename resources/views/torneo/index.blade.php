<!-- 
    Vista principal de torneo.
    Contiene el botón para crear un nuevo torneo.
    Muestra todos los torneos del año actual.
    Tiene opciones para buscar un torneo por categoría y por año.
-->

<!-- Plantilla a usar -->
@extends('layouts.master')

<!-- Agregar el título de la página -->
@section('title', 'Torneo')

<!-- Agregar los elementos del breadcrumb -->
@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li class="active">Torneo</li>
@endsection

<!-- Agregar el contenido de la página -->
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Botón para crear un nuevo torneo -->
            <div class="col-xs-12" style="padding-bottom: 15px;">
                <form>
                    <a class="btn btn-success" href="{{ route("torneo.create") }}"><i class="fa fa-plus"></i> Crear Torneo</a>
                </form>
            </div>
        </div>
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
        <div class="row">
            <div class="col-xs-2"></div>
            <div class="col-xs-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Torneos del a&ntilde;o {{ $anioServer }}</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <!-- Tabla que contiene todos los torneos del año(actual) -->
                            <div class="col-xs-12">
                                <!-- Verificación de la existencia de torneos para el año actual -->
                                @if($inexistentes != 7)
                                    <!-- Creación de la tabla con los torneos del año actual -->
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="torneosAnio">
                                            <thead>
                                                <tr>
                                                    <th>Categor&iacute;a</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($torneos as $torneo)
                                                    <tr>
                                                        <td>{{ $torneo['categoria'] }}</td>
                                                        @if($torneo['id'] == 0)
                                                            <td><b>No se ha creado un torneo</b></td>
                                                            <td></td>
                                                        @else
                                                            <td> <a href="{{ route('torneo.edit', $torneo['id']) }}" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Editar</a></td>
                                                            <td>
                                                                <form action="/torneo/{{ $torneo['id'] }}" method="POST">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    {{ csrf_field() }}
                                                                    <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i> Desactivar</button>
                                                                </form>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 class="text-center">Ning&uacute;n torneo ha sido creado para el a&ntilde;o {{ $anioServer }}</h4>
                                @endif
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!--/.col (middle) -->
            <div class="col-xs-2"></div>
        </div>
        <div class="row">
            <div class="col-xs-2"></div>
            <div class="col-xs-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buscar torneos</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <form role="form" action="/buscarTorneos" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="col-lg-1">
                                            <label for="anio">A&ntilde;o</label>
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="number" class="form-control" id="anio" name="anio"placeholder="A&ntilde;o">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-2">
                                            <label for="categoria">Categor&iacute;a</label>
                                        </div>
                                        <div class="col-lg-3" id="categoria">
                                            <select class="form-control" name="categoria">
                                                <option></option>
                                                @if(count($categorias) != 0)
                                                    @foreach($categorias as $categoria)
                                                        <option>{{ $categoria->nombre }}</option>
                                                    @endforeach
                                                @else
                                                        <option>No se ha registrado ninguna categor&iacute;a</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="buscar">Buscar</button>
                                </form>
                                <div class="table-responsive">
                                    @if (session()->has('torneosEncontrados'))
                                        <hr>
                                        <h5><b>Torneos encontrados</b></h5>
                                        <table class="table table-hover" id="torneosEncontrados">
                                            <thead>
                                                <tr>
                                                    <th>Categor&iacute;a</th>
                                                    <th>A&ntilde;o</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count(session()->get('torneosEncontrados')) == 0)
                                                    <tr>
                                                        <td><b>No se ha encontrado ningún torneo</b></td>
                                                        <td></td>
                                                    </tr>
                                                @else
                                                    @foreach(session()->get('torneosEncontrados') as $torneoEncontrado)
                                                        <tr>
                                                            <td>{{ $torneoEncontrado[2] }}</td>
                                                            <td>{{ $torneoEncontrado[1] }}</td>
                                                            <td> <a href="{{ route('torneo.edit', $torneoEncontrado[0]) }}" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Editar</a></td>
                                                            <td>
                                                                <form action="/torneo/{{ $torneoEncontrado[0] }}" method="POST">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    {{ csrf_field() }}
                                                                    <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i> Desactivar</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!--/.col (middle) -->
            <div class="col-xs-2"></div>
        </div>
        
        

    </div>

@endsection

@section('scriptsPersonales')
@endsection
