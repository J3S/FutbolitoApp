<!-- 
    Vista principal de torneo.
    Contiene el botón para crear un nuevo torneo
    Muestra todos los torneos del año actual
    Tiene opciones para buscar un torneo por categoría y por año
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
                    <button type="button" id="nuevoTorneoButton" class="btn btn-success" onclick="window.location='{{ route("torneo.create") }}'"><i class="fa fa-plus"></i> Nuevo Torneo</button>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Tabla que contiene todos los torneos del año(actual) -->
            <div class="col-xs-12">
                <!-- Verificación de la existencia de torneos para el año actual -->
                @if($inexistentes != 7)
                    <!-- Creación de la tabla con los torneos del año actual -->
                    <h4 class="text-center">Torneos del a&ntilde;o {{ $anioServer }}</h4>
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
                                            <td> <a href="{{ route('torneo.edit', $torneo['id']) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Editar</a></td>
                                            <td>
                                                <form action="/torneo/{{ $torneo['id'] }}" method="POST">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-minus"></i> Desactivar</button>
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
        <hr>
        <div class="row">
            <div class="col-xs-12">
                <h4>Buscar torneos</h4>
                <form>
                    <div class="form-group">
                        <div class="col-md-1">
                            <label for="anio">A&ntilde;o</label>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="anio" placeholder="A&ntilde;o">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-1">
                            <label for="categoria">Categor&iacute;a</label>
                        </div>
                        <div class="col-md-2" id="categoria">
                            <select class="form-control">
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
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </div>
        </div>
            
        

    </div>

@endsection
