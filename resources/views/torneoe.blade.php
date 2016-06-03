@extends('layouts.master')

@section('title', 'Editar Torneo')

@section('contentHeaderTitle', 'Editar Torneo')

@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('torneo') }}">Torneo</a></li>
    <li class="active">Editar</li>
@endsection

@section('content')
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
                            <label for="fecha_inicio">Fecha de inicio</label>
                        </div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="fechaInicio" name="fecha_inicio" placeholder="Ingrese fecha de inicio del torneo" value="{{ $torneo->fecha_inicio }}">
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="col-md-4">
                            <label for="fecha_fin">Fecha de finalizaci&oacute;n</label>
                        </div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="fechaFin" name="fecha_fin" placeholder="Ingrese fecha de inicio del torneo" value="{{ $torneo->fecha_fin }}">
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