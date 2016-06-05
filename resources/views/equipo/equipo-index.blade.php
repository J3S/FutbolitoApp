@extends('layouts.master')

@section('title', 'Crear Equipo')

@section('contentHeaderTitle', 'Crear Equipo')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{!!route('equipo.index')!!}">Equipo</a></li>
@endsection

@section('content')
    <div class="col-xs-1"></div>
    <div class="col-xs-10">
        <h3 style="margin-top:0;">Lista de Equipos</h3>
        <table class="table table-striped">
            <tr>
                <th>id</th>
                <th>Nombre</th>
                <th>DT</th>
                <th>Acci&oacute;n</th>
            </tr>
            @foreach ($equipos as $equipo)
            <tr>
                <td>{{ $equipo->id }}</td>
                <td>{{ $equipo->nombre }}</td>
                <td>{{ $equipo->director_tecnico }}</td>
                <td>
                    <a class="btn btn-primary" href="{!! route('equipo.edit', ['equipo' => $equipo->id]) !!}">Editar</a>
                    <form style="display:inline-block" action="{!!route('equipo.destroy', ['equipo' => $equipo->id])!!}" method="POST">
                        <input name="_method" type="hidden" value="DELETE">
                        {{ csrf_field() }}
                        <button class="btn btn-danger" type="submit" >Desactivar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="col-xs-1"></div>
@endsection
