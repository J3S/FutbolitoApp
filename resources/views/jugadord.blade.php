@extends('layouts.master')

@section('title', 'Desactivar Jugador')

@section('contentHeaderTitle', 'Desactivar Jugador')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="#">Jugador</a></li>
    <li class="active">Desactivar</li>
@endsection

@section('content')
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <!-- general form elements -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Desactivar Jugador</h3>
            </div><!-- /.box-header -->
            <!-- search form -->
            <div class="box-body">
                <div class="col-xs-12">
                    <form action="#" method="get">
                        <div class="form-group col-xs-12 separador">
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
            <!-- form start -->
            <form role="form">
                <div class="box-body">
                    <div class="col-xs-12">
                        <div class="form-group col-xs-12 separador">
                            <label class="header-group">Buscar por campos</label>
                        </div>
                        <div class="form-group col-xs-12 col-sm-2">
                            <label for="torneo">Torneo</label>
                        </div>
                        <div class="form-group col-xs-12 col-sm-10">
                            <select class="form-control" id="torneo">
                                <option>-- No seleccionado --</option>
                                <option>Opcion 1</option>
                                <option>Opcion 2</option>
                                <option>Opcion 1</option>
                                <option>Opcion 1</option>
                            </select>
                        </div>
                        <div class="form-group col-xs-12 col-sm-2">
                            <label for="equipo">Equipo</label>
                        </div>
                        <div class="form-group col-xs-12 col-sm-10">
                            <select class="form-control" id="equipo">
                                <option>-- No seleccionado --</option>
                                <option>Opcion 1</option>
                                <option>Opcion 2</option>
                                <option>Opcion 1</option>
                                <option>Opcion 1</option>
                            </select>
                        </div>
                        <div class="form-group col-xs-12 col-sm-2">
                            <label for="jugador">Jugador</label>
                        </div>
                        <div class="form-group col-xs-12 col-sm-10">
                            <select class="form-control" id="jugador">
                                <option>-- No seleccionado --</option>
                                <option>Opcion 1</option>
                                <option>Opcion 2</option>
                                <option>Opcion 1</option>
                                <option>Opcion 1</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group col-xs-12 separador">
                            <label class="header-group">Informaci&oacute;n del jugador</label>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group col-xs-12 col-sm-6">
                            <label for="inputNombre">Nombres</label>
                            <input type="text" class="form-control" id="inputNombre" placeholder="Ingrese nombre" disabled>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label for="inputApellido">Apellidos</label>
                            <input type="text" class="form-control" id="inputApellido" placeholder="Ingrese apellido" disabled>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6">
                            <label for="inputCedula">C&eacute;dula</label>
                            <input type="text" class="form-control" id="inputCedula" placeholder="Ingrese c&eacute;dula" disabled>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="inputNumCam">N&uacute;mero de camiseta</label>
                                <input type="text" min="0" class="form-control" id="inputNumCam" placeholder="Ingrese N&uacute;mero de camiseta" disabled>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="inputPeso">Peso</label>
                                <input type="text" min="0" step="any" class="form-control" id="inputPeso" placeholder="Ingrese peso" disabled>
                            </div>
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