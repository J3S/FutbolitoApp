@extends('layouts.master')

@section('title', 'Crear Torneo')

@section('contentHeaderTitle', 'Crear Torneo')

@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('torneo') }}">Torneo</a></li>
    <li class="active">Crear</li>
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
                                <option>Equipo1</option>
                                <option>Equipo2</option>
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
                <h3 class="box-title">Crear Torneo</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="/torneo" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="categoria">Categor&iacute;a</label>                        
                        </div>
                        <div class="col-md-8">
                            <select class="form-control" id="categoria" name="categoria">
                                <option></option>
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
                            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" placeholder="Ingrese fecha de inicio del torneo">
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="fechaFin">Fecha de finalizaci&oacute;n</label>
                        </div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="fechaFin" name="fechaFin" placeholder="Ingrese fecha de inicio del torneo">
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
                                    <ul class="list-group">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <div class="col-xs-2"></div>
                    <div class="col-xs-8">
                        <button type="submit" class="btn btn-primary">Limpiar</button>
                        <button type="submit" class="btn btn-success pull-right">Guardar</button>
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
        if($('#searchEquipoNombre').val() != ""){
            $("#equipoTorneo ul").append('<li class="list-group-item col-sm-10">'+$('#searchEquipoNombre').val()+'</li>');
            $("#equipoTorneo ul").append('<button type="button" class="btn btn-success btn-sm col-sm-1" data-toggle="modal" data-target="#equiposModal"><i class="fa fa-plus"></i></button>');
            $("#equipoTorneo ul").append('<button type="button" class="btn btn-danger btn-sm col-sm-1"><i class="fa fa-minus"></i></button>');
        }
        $('#equiposModal').modal('hide');

    });
    document.getElementById("agregarEquipoToLista2").addEventListener("click", function(){
        var equipoAgregar = "";
        if($('#listaEquipo').val() != ""){
            $("#equipoTorneo ul").append('<li class="list-group-item col-sm-10">'+$('#listaEquipo').val()+'</li>');
            $("#equipoTorneo ul").append('<button type="button" class="btn btn-success btn-sm col-sm-1" data-toggle="modal" data-target="#equiposModal"><i class="fa fa-plus"></i></button>');
            $("#equipoTorneo ul").append('<button type="button" class="btn btn-danger btn-sm col-sm-1"><i class="fa fa-minus"></i></button>');
        }
        $('#equiposModal').modal('hide');

    });
    </script>
@endsection