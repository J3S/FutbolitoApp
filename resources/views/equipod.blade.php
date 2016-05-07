@extends('layouts.master')

@section('title', 'Desactivar Equipo')

@section('contentHeaderTitle', 'Desactivar Equipo')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="#">Equipo</a></li>
    <li class="active">Desactivar</li>
@endsection

@section('content')
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <!-- general form elements -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Desactivar Equipo</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="col-xs-12">
                    <form action="#" method="get">
                        <div class="form-group col-xs-12 separador text-center">
                            <label class="header-group">Buscar por nombre</label>    
                        </div>
                        <div class="input-group">
                            <input type="text" name="searchNombre" id="searchNombre" class="form-control" placeholder="Buscar por nombre">
                            <span class="input-group-btn">
                            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="box-body">
                <div class="col-xs-12">
                    <div class="form-group col-xs-12 col-sm-12 separador text-center">
                        <label class="header-group" for="listaEquipo">Buscar en la lista de equipos</label>
                    </div>
                </div>
                <div class="form-group col-xs-12 col-sm-12">
                    <select class="form-control" id="listaEquipo">
                        <option>-- No seleccionado --</option>
                        <option>Opcion 1</option>
                        <option>Opcion 2</option>
                        <option>Opcion 1</option>
                        <option>Opcion 1</option>
                    </select>
                </div>
            </div>
            <!-- form start -->
            <form role="form">
                <div class="box-body">
                    <div class="form-group col-xs-12">
                        <div class="form-group col-xs-12 col-sm-12 separador">
                            <label class="header-group" for="listaEquipo">Informaci&oacute;n del equipo</label>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="form-group col-xs-12 col-sm-6">
                            <label for="inputNombre">Nombre</label>
                            <input type="text" class="form-control" id="inputNombre" placeholder="Ingrese nombre equpo" disabled>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label for="inputEntrenador">Entrenador</label>
                            <input type="text" class="form-control" id="inputEntrenador" placeholder="Ingrese nombre entrenador" disabled>
                        </div>
                        <div class="form-group col-xs-12">
                            <div class="col-xs-12" style="padding-left: 0px;">
                                <label for="inputJugadores" style="vertical-align: center;">Jugadores</label>
                            </div>
                            <select multiple="" size="8" class="form-control" id="inputJugadores" disabled>
                                <option>Cras justo odio</option>
                                <option>Dapibus ac facilisis in</option>
                                <option>Morbi leo risus</option>
                                <option>Porta ac consectetur ac</option>
                                <option>Vestibulum at eros</option>
                            </select>
                        </div>
                    </div>
                </div><!-- /.box-body -->

              <div class="box-footer">
                <div class="col-xs-2"></div>
                <div class="col-xs-8">
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary">Limpiar</button>
                    </div>
                    <div class="col-xs-4 pull-right">
                        <button type="submit" class="btn btn-danger">Desactivar</button>
                    </div>
                </div>
                <div class="col-xs-2"></div>
              </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <div class="col-xs-2"></div>
@endsection