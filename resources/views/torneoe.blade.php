@extends('layouts.master')

@section('title', 'Editar Torneo')

@section('contentHeaderTitle', 'Editar Torneo')

@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('torneo') }}">Torneo</a></li>
    <li class="active">Editar</li>
@endsection

@section('content')
    <div class="modal fade" id="equiposModal" tabindex="-1" role="dialog" aria-labelledby="equiposModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="equiposModalLabel">Agregar Equipos</h4>
                </div>
                <div class="modal-body">
                    <form action="#" method="get">
                        <div class="form-group">
                            <label class="control-label" for="searchEquipoNombre">Buscar equipo</label>
                            <div class="input-group">
                                <input type="text" name="searchEquipoNombre" id="searchEquipoNombre" class="form-control" placeholder="Buscar por nombre">
                                <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                            <button type="button" class="btn btn-primary" id="agregarEquipoToLista1">Agregar Equipo</button>    
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="listaEquipo">Buscar en la lista de equipos</label>
                            <select class="form-control" id="listaEquipo">
                                <option></option>
                                @foreach($equipos as $equipo)
                                    <option>{!! $equipo['nombre'] !!}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" id="agregarEquipoToLista2">Agregar Equipo</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="col-xs-2"></div>
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
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Torneo</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="/torneo/{{ $torneo->id }}" method="POST">
                <input name="_method" type="hidden" value="PUT">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="categoria">Categor&iacute;a</label>                        
                        </div>
                        <div class="col-md-8">
                            <select class="form-control" id="categoria" name="categoria">
                                <option disabled="disabled">{{ $torneo->categoria }}</option>
                                <option class="select-dash" disabled="disabled">----</option>
                                <option>Super Junior</option>
                                <option>Junior</option>
                                <option>Senior</option>
                            </select>                            
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="fechaInicio">Fecha de inicio</label>
                        </div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" placeholder="Ingrese fecha de inicio del torneo" value="{{ $torneo->fechaInicio }}">
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="fechaFin">Fecha de finalizaci&oacute;n</label>
                        </div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="fechaFin" name="fechaFin" placeholder="Ingrese fecha de inicio del torneo" value="{{ $torneo->fechaFin }}">
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="equipos" style="vertical-align: center;">Equipos</label>
                        </div>
                        <div class="col-md-8">
                            <div class="col-sm-12" style="padding-left: 0px;">
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#equiposModal"><i class="fa fa-plus"></i> Agregar Equipo</button>
                            </div>
                            <div class="col-sm-12" style="padding-left: 0px;">
                                <div id="equipoTorneo" style="padding-left: 0px;">
<!--                                     <ul class="list-group">
                                    </ul> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <div class="col-xs-2"></div>
                    <div class="col-xs-8">
                        <button type="submit" class="btn btn-primary">Limpiar</button>
                        <button type="submit" class="btn btn-success pull-right">Actualizar</button>
                    </div>
                    <div class="col-xs-2"></div>
                </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <div class="col-xs-2"></div>
@endsection
@section('scriptsPersonales')
    <script>
    $('#equiposModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var modal = $(this)
    })
    document.getElementById("agregarEquipoToLista1").addEventListener("click", function(){
        if($('#searchEquipoNombre').val() != "") {
            if($('#equipoTorneo').children().length == 0 ) {
                var table = document.createElement('table');
                table.className += "table";
                table.id = 'equiposTable';
                var thead = document.createElement('thead');
                var row = document.createElement('tr');
                var th = document.createElement('th');
                var textH = document.createTextNode('Equipo');
                th.appendChild(textH);
                row.appendChild(th);
                th = document.createElement('th');
                textH = document.createTextNode('Cambiar');
                th.appendChild(textH);
                row.appendChild(th);
                th = document.createElement('th');
                textH = document.createTextNode('Eliminar');
                th.appendChild(textH);
                row.appendChild(th);
                thead.appendChild(row);
                table.appendChild(thead);
                var tbody = document.createElement('tbody');
                table.appendChild(tbody);
                $('#equipoTorneo').append(table);
            }
            $("#equiposTable tbody").append('<tr><td>'+$('#searchEquipoNombre').val()+'</td><td><button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#equiposModal"><i class="fa fa-exchange"></i></button></td><td><button type="button" class="btn btn-danger btn-xs" onclick="eliminarRow(this)"><i class="fa fa-minus"></i></button>3</td></tr>');
        }
        $('#equiposModal').modal('hide');
    });
    document.getElementById("agregarEquipoToLista2").addEventListener("click", function(){
        var equipoAgregar = "";
        if($('#listaEquipo').val() != "") {
            if($('#equipoTorneo').children().length == 0 ) {
                var table = document.createElement('table');
                table.className += "table";
                table.id = 'equiposTable';
                var thead = document.createElement('thead');
                var row = document.createElement('tr');
                var th = document.createElement('th');
                var textH = document.createTextNode('Equipo');
                th.appendChild(textH);
                row.appendChild(th);
                th = document.createElement('th');
                textH = document.createTextNode('Cambiar');
                th.appendChild(textH);
                row.appendChild(th);
                th = document.createElement('th');
                textH = document.createTextNode('Eliminar');
                th.appendChild(textH);
                row.appendChild(th);
                thead.appendChild(row);
                table.appendChild(thead);
                var tbody = document.createElement('tbody');
                table.appendChild(tbody);
                $('#equipoTorneo').append(table);
            }
            $("#equiposTable tbody").append('<tr><td>'+$('#listaEquipo').val()+'</td><td><button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#equiposModal"><i class="fa fa-exchange"></i></button></td><td><button type="button" class="btn btn-danger btn-xs" onclick="eliminarRow(this)"><i class="fa fa-minus"></i></button></td></tr>');
        }
        $('#equiposModal').modal('hide');
    });
    function eliminarRow(buttonTriggered){
        $(buttonTriggered).parent().parent().remove();
    }
    </script>
@endsection