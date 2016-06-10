<!-- 
    Vista principal de jugador.
    Contiene las siguientes opciones:
    * Crear jugador
    * Búsqueda de jugadores a través de filtros con el fin de
    modificar los datos permitidos o eliminarlos.
-->
<!-- Plantilla -->
@extends('layouts.master')
<!-- Titulo -->
@section('title', 'Jugador')
@section('contentHeaderTitle', 'Jugador')

<!-- Elementos Breadcrumb -->
@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li class="active">Jugador</li>
@endsection

<!-- Contenido de la pagina -->
@section('content')

    <div class="col-xs-12" style="padding-bottom: 15px;">
        <form><!--  -->
        	<!-- Boton para crear un nuevo jugador -->
            <button type="button" id="nuevoJugadorButton" class="btn btn-success" onclick="window.location='{{ route("jugador.create") }}'"><i class="fa fa-plus"></i> Nuevo Jugador</button>
        </form>
    </div>
	<div class="col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Buscar Jugador</h3>
			</div><!-- /.box-header -->
            <form role="form" action="/selectJugador" method="post">
            {!! csrf_field() !!}
				<div class="box-body">
					<div class="form-group col-xs-12 col-sm-4">
						<label for="nombJug">Nombres: </label>
						<!-- Campo para ingresar nombre para busqueda del jugador -->
						<input type="text" class="form-control" id="nombJug" name="nombJug">
					</div>
					<div class="form-group col-xs-12 col-sm-4">
						<label for="apellJug">Apellidos: </label>
						<!-- Campo para ingresar apellidos para busqueda del jugador -->
						<input type="text" class="form-control" id="apellJug" name="apellJug">
					</div>

					<div class="form-group col-xs-12 col-sm-4">
						<label for="cedJug">Cedula: </label>
						<!-- Campo para ingresar cedula para busqueda del jugador -->
						<input type="text" class="form-control" id="cedJug" name="cedJug">
					</div>
					<div class="form-group col-xs-12 col-sm-4">
                        <label for="listEquipo">Categoria</label>
                        <!-- Campo para seleccionar el equipo para busqueda del jugador -->
                        <select class="form-control input" id="categoria" name="categoria">
                        	<option selected="selected"></option>
                            @foreach($categorias as $categoria)
                               <option value="{{ $categoria['id'] }}">{{ $categoria->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
					<div class="form-group col-xs-12 col-sm-4">
                        <label for="listEquipo">Equipo</label>
                        <!-- Campo para seleccionar el equipo para busqueda del jugador -->
                        <select class="form-control input" id="equipo" name="equipo">
                        	<option selected="selected"></option>
                            @foreach($equipos as $equipo)
                               <option value="{{ $equipo['id'] }}">{{ $equipo->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12" >
	                    <button type="submit" class="btn btn-success">Buscar</button>
	                </div>
				</div>
			</form>
		</div>
	</div>
	
<!-- Seccion para mostrar los resultadsos de la busqueda de jugadores -->
	
	@if(!empty($jugadores) and count($jugadores) != 0)
		<div class="col-xs-1"></div>
		    <div class="col-xs-15">
		        <h3 style="margin-top:0;">Lista de Jugadores</h3>
		        <table class="table table-striped">
		            <tr>
		                <!-- <th>id</th> -->
		                <th>Nombres</th>
		                <th>Apellidos</th>
		                <th>Equipo</th>
		                <th>Categor&iacute;a</th>
		                <th>C&eacute;dula</th>
		                <th>Acci&oacute;n</th>
		            </tr>
		            @foreach ($jugadores as $jugador)
		            <tr>
		                <td>{{ $jugador->nombres}}</td>
		                <td>{{ $jugador->apellidos}}</td>
		                @foreach($equipos as $equipo)
		                	@if($jugador->id_equipo == $equipo->id)
		                		<td>{{ $equipo->nombre}}</td>
		                	@endif
		                @endforeach
		                <td>{{ $jugador->categoria}}</td>
		                <td>{{ $jugador->identificacion}}</td>
		                <td>
		                    <a class="btn btn-warning btn-sm" href="{!! route('jugador.edit', ['jugador' => $jugador->id]) !!}"><i class="fa fa-pencil-square-o fa-lg"></i></a>
		                    <!-- <a class="btn btn-warning btn-sm" href="{!! route('equipo.edit', ['equipo' => $equipo->id]) !!}"><i class="fa fa-pencil"></i> Editar</a> -->
		                    <form style="display:inline-block" action="{!!route('jugador.destroy', ['jugador' => $jugador->id])!!}" method="POST">
		                        <input name="_method" type="hidden" value="DELETE">
		                        {{ csrf_field() }}
		                        <button class="btn btn-danger btn-sm" type="submit" ><i class="fa fa fa-times fa-lg"></i></button>
		                        <!-- <button class="btn btn-danger btn-sm" type="submit" ><i class="fa fa-minus"></i> Desactivar</button> -->
		                    </form>
		                </td>
		            </tr>
		            @endforeach
		        </table>
		    </div>
	    <div class="col-xs-1"></div>
	@endif  
    @if(!empty($jugadores) and count($jugadores) == 0)
    	<h4 class="text-center">La búsqueda no ha coincidido con ning&uacute;n jugador.</h4>
    	<h5 class="text-center">Seleccione opciones m&aacute;s generales e intente de nuevo.</h4>
    @endif
@endsection


