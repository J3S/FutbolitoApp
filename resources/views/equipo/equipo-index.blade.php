@extends('layouts.master')

@section('title', 'Equipos')

@section('contentHeaderTitle', 'Equipos')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{!!route('equipo.index')!!}">Equipo</a></li>
@endsection

@section('content')
    <div class="col-xs-12" style="padding-bottom: 15px;">
        <form>
            <button type="button" id="nuevoEquipoButton" class="btn btn-success" onclick="window.location='{{ route("equipo.create") }}'"><i class="fa fa-plus"></i> Nuevo Equipo</button>
        </form>
    </div>
    <div class="col-xs-1"></div>
    <div class="col-xs-10">
        <h3 style="margin-top:0;">Lista de Equipos</h3>
        <table class="table table-striped">
            <tr>
                <!-- <th>id</th> -->
                <th>Nombre</th>
                <th>Entrenador</th>
                <th>Categoria</th>
                <th>Perfil</th>
                <th>Acci&oacute;n</th>
            </tr>
            @foreach ($equipos as $equipo)
            <tr>
                <!-- <td>{{ $equipo->id }}</td> -->
                <td>{{ $equipo->nombre }}</td>
                <td>{{ $equipo->director_tecnico }}</td>
                <td>{{ $equipo->categoria }}</td>
                <td><a href="{!! route('equipo.show', ['equipo' => $equipo->id]) !!}">Ver Jugadores</a></td>
                <td>
                    <a class="btn btn-success btn-sm" href="{!! route('equipo.edit', ['equipo' => $equipo->id]) !!}"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                    <form style="display:inline-block" action="{!!route('equipo.destroy', ['equipo' => $equipo->id])!!}" method="POST">
                        <input name="_method" type="hidden" value="DELETE">
                        {{ csrf_field() }}
                        <button class="btn btn-success btn-sm" type="submit" ><i class="fa fa-times fa-lg"></i></button>
                    </form>
                    <!-- <a class="btn btn-danger btn-sm" href="{!! route('equipo.index') !!}"><i class="fa fa-times fa-lg"></i></a> -->
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="col-xs-1"></div>
@endsection
