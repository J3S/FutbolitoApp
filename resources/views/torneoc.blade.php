@extends('layouts.master')

@section('title', 'Crear Torneo')

@section('contentHeaderTitle', 'Crear Torneo')

@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('torneo') }}">Torneo</a></li>
    <li class="active">Crear</li>
@endsection

@section('content')
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Crear Torneo</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form">
                <div class="box-body">
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="inputCategoria">Categor&iacute;a</label>                        
                        </div>
                        <div class="col-md-8">
                            <select class="form-control" id="inputCategoria" name="inputCategoria">
                                <option> -- No seleccionado -- </option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>                            
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="inputFechaInicio">Fecha de inicio</label>
                        </div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="inputFechaInicio" name="inputFechaInicio" placeholder="Ingrese fecha de inicio del torneo">
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="inputFechaFin">Fecha de finalizaci&oacute;n</label>
                        </div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="inputFechaFin" name="inputFechaFin" placeholder="Ingrese fecha de inicio del torneo">
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="inputJugadores" style="vertical-align: center;">Equipos</label>
                        </div>
                        <div class="col-md-6">
                            <select multiple="" size="10" class="form-control" id="inputJugadores">
                                <option>Cras justo odio</option>
                                <option>Dapibus ac facilisis in</option>
                                <option>Morbi leo risus</option>
                                <option>Porta ac consectetur ac</option>
                                <option>Vestibulum at eros</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success"><i class="fa fa-plus"></i></button>
                            <button type="button" class="btn btn-danger"><i class="fa fa-minus"></i></button>
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