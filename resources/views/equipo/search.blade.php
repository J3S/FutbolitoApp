@extends('layouts.master')

@section('title', 'Equipos')

@section('contentHeaderTitle', 'Equipos')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{!!route('equipo.index')!!}">Equipo</a></li>
@endsection

@section('content')
    <div class="col-xs-12" style="padding-bottom: 15px;">
    @include('flash::message')
        <form>
            <button type="button" id="nuevoEquipoButton" class="btn btn-success" onclick="window.location='{{ route("equipo.create") }}'"><i class="fa fa-plus"></i> Crear Equipo</button>
        </form>
    </div>

    <div class="col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Buscar Equipo</h3>
			</div><!-- /.box-header -->
            <form role="form" action="{!!route('equipo.search')!!}" method="post">
            {!! csrf_field() !!}
				<div class="box-body">
					<div class="form-group col-xs-12 col-sm-6">
						<label for="nombEquipo">Nombre: </label>
						<!-- Campo para ingresar nombre para busqueda del equipo -->
						<input type="text" class="form-control" id="nombEquipo" name="nombEquipo">
					</div>
					<div class="form-group col-xs-12 col-sm-6">
                        <label for="listEquipo">Categoria</label>
                        <!-- Campo para seleccionar el equipo para busqueda del equipo -->
                        <select class="form-control input" id="categoria" name="categoria">
                        	<option selected="selected"></option>
                            @foreach($categorias as $categoria)
                               <option value="{{ $categoria['id'] }}">{{ $categoria->nombre}}</option>
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
@endsection
