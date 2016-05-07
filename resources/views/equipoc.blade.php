@extends('layouts.master')

@section('title', 'Crear Equipo')

@section('contentHeaderTitle', 'Crear Equipo')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="#">Equipo</a></li>
    <li class="active">Crear</li>
@endsection

@section('content')
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Crear Equipo</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form">
                <div class="box-body">
                    <div class="form-group col-xs-12">
                        <label for="inputNombre">Nombre</label>
                        <input type="text" class="form-control" id="inputNombre" placeholder="Ingrese nombre equipo">
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="inputEntrenador">Entrenador</label>
                        <input type="text" class="form-control" id="inputEntrenador" placeholder="Ingrese nombre entrenador">
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="col-xs-2" style="padding-left: 0px;">
                            <label for="inputJugadores" style="vertical-align: center;">Jugadores</label>
                        </div>
                        <div class="col-xs-8"></div>
                        <div class="col-xs-1">
                                <button type="button" class="btn btn-success"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="col-xs-1">
                                <button type="button" class="btn btn-danger"><i class="fa fa-minus"></i></button>
                        </div>
                        <select multiple="" size="11" class="form-control" id="inputJugadores">
                            <option>Cras justo odio</option>
                            <option>Dapibus ac facilisis in</option>
                            <option>Morbi leo risus</option>
                            <option>Porta ac consectetur ac</option>
                            <option>Vestibulum at eros</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="inputFile">Logo</label>
                        <input id="inputFile" type="file">
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