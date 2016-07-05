<!--
V2.0 Branny
    -add atributo name a todos los campos para poderlos recibir en el servidor
    -add campos al form: email, tefl
    -add action al formulario apuntando a la ruta 'jugador.store'(ver lista de rutas)
-->


@extends('layouts.master')

@section('title', 'Crear Jugador')

@section('contentHeaderTitle', 'Crear Jugador')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('jugador') }}">Jugador</a></li>
    <li class="active">Crear</li>
@endsection
<style>
    .form-group .required:after {
        position:absolute;
        content:'*';
        color:red;
        margin-left: 5px;
        top:0;
    }

    .campoRequerido {
        color:red; 
        font-style:italic; 
        font-size:0.9em;
    }
</style>
@section('content')
    <div class="col-xs-12">
        <div class="col-xs-2"></div>
        <!-- Alert que muestra todos los errores en los campos si falla al hacer post -->
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
                <h3 class="box-title">Crear Jugador</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{!!route('jugador.store')!!}" method="post">
                {!! csrf_field() !!}
                <div class="box-body">
                    <div class="form-group col-xs-12">
                        <label class="required" for="inputNombre">Nombres</label>
                        <input type="text" class="form-control" id="inputNombre" name="nombres" placeholder="Ingrese nombre">
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="required" for="inputApellido">Apellidos</label>
                        <input type="text" class="form-control" id="inputApellido" name="apellidos" placeholder="Ingrese apellido">
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="fecha_nac">Fecha Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" placeholder="Ingrese fecha de nacimiento">
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="inputCedula">C&eacute;dula</label>
                        <input type="text" class="form-control" id="inputCedula" name="identificacion" placeholder="Ingrese c&eacute;dula">
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="inputPeso">Rol</label>
                            <input type="text" class="form-control" id="inputRol" name="rol" placeholder="Ej: Defensa">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputCorreo">Email</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="ejemplo@gmail.com">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputPeso">Tel&eacute;fono</label>
                            <input type="text" class="form-control" id="inputTelefono" name="telefono" placeholder="Ingrese Telefono">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="peso">Peso (Kg)</label>
                            <input type="number" min="0" step="0.1" class="form-control" id="peso" name="peso" placeholder="Ej: 70.5">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="inputNumCam">N&uacute;mero de camiseta</label>
                            <input type="number" min="0" class="form-control" id="inputNumCam" name="num_camiseta" placeholder="Ingrese N&uacute;mero">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
                        <label for="categoria">Categor&iacute;a</label>
                        <!-- Campo para seleccionar la categoría del jugador -->
                        <select class="form-control input" id="categoria" name="categoria">
                            <option selected="selected"></option>
                            @foreach($categorias as $categoria)
                               <option value="{{ $categoria['nombre'] }}">{{ $categoria->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
                        <label for="equipo">Equipo</label>
                        <!-- Campo para seleccionar el equipo para busqueda del jugador -->
                        <select class="form-control input" id="equipo" name="equipo">
                            <option selected="selected"></option>
                            @foreach($equipos as $equipo)
                               <option value="{{ $equipo['id'] }}">{{ $equipo->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label class="campoRequerido">(*) Campo requerido</label>
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
