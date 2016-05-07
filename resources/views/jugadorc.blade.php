@extends('layouts.master')

@section('title', 'Crear Jugador')

@section('contentHeaderTitle', 'Crear Jugador')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="#">Jugador</a></li>
    <li class="active">Crear</li>
@endsection

@section('content')
            <div class="col-xs-2"></div>
            <div class="col-xs-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Crear Jugador</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form">
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="inputNombre">Nombres</label>
                                <input type="text" class="form-control" id="inputNombre" placeholder="Ingrese nombre">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="inputApellido">Apellidos</label>
                                <input type="text" class="form-control" id="inputApellido" placeholder="Ingrese apellido">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="inputCedula">C&eacute;dula</label>
                                <input type="text" class="form-control" id="inputCedula" placeholder="Ingrese c&eacute;dula">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="inputFechaNac">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="inputFechaNac" placeholder="Ingrese fecha de nacimiento">
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="inputNumCam">N&uacute;mero de camiseta</label>
                                    <input type="number" min="0" class="form-control" id="inputNumCam" placeholder="Ingrese N&uacute;mero de camiseta">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="inputPeso">Peso</label>
                                    <input type="number" min="0" step="any" class="form-control" id="inputPeso" placeholder="Ingrese peso">
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
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </div>
                        <div class="col-xs-2"></div>
                      </div>
                    </form>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
            <div class="col-xs-2"></div>
@endsection