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
                    <button type="button" id="nuevo-torneo-button" class="btn btn-success" onclick="window.location='{{ route("torneo.create") }}'"><i class="fa fa-plus"></i> Nuevo Torneo</button>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Tabla que contiene todos los torneos del año(actual) -->
            <div class="col-xs-12">
                <!-- Verificación de la existencia de torneos para el año actual -->
                @if(!empty($torneos['id_categoria']))
                    <!-- Creación de la tabla con los torneos del año actual -->
                     <h4 class="text-center">Torneos del a&ntilde;o {{ $anioServer }}</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="torneos-anio">
                            <thead>
                                <tr>
                                    <th>Categor&iacute;a</th>
                                    <th>Modificar</th>
                                    <th>Desactivar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($torneos as $torneo)
                                    <tr>
                                        <td>test</td>
                                        <td></td>
                                        <td></td>
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

        <div class="row">
            <div class="col-xs-12">
                
            </div>
        </div>
            
        

    </div>

@endsection
