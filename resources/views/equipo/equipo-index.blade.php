@extends('layouts.master')

@section('title', 'Equipos')

@section('contentHeaderTitle', 'Equipos')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{!!route('equipo.index')!!}">Equipo</a></li>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<style>
.alert {
    z-index: 99; 
    position: absolute; 
    left: 65%;
}
</style>
@section('content')
    <div class="col-xs-12" style="padding-bottom: 15px;">
    @include('flash::message')
    @include('modals.delete')
        <form>
            <button type="button" id="nuevoEquipoButton" class="btn btn-success" onclick="window.location='{{ route("equipo.create") }}'"><i class="fa fa-plus"></i> Crear Equipo</button>
        </form>
    </div>

    <div class="col-xs-12">
        <div class="box box-primary">
            <!-- mensajes de errro de validacion -->
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
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Lista de Equipos</h3>
                <div class="table-responsive">
                    <table class="dataTable table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Entrenador</th>
                                <th>Categoria</th>
                                <th>Perfil</th>
                                <th>Editar</th>
                                <th>Desactivar</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($equipos as $equipo)
                            <tr>
                                <td>{{ $equipo->nombre }}</td>
                                <td>{{ $equipo->director_tecnico }}</td>
                                <td>{{ $equipo->categoria }}</td>
                                <td><a href="{!! route('equipo.show', ['equipo' => $equipo->id]) !!}">Ver Jugadores</a></td>
                                <td>
                                    <a class="btn btn-warning btn-xs" href="{!! route('equipo.edit', ['equipo' => $equipo->id]) !!}"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                                </td>
                                <td>
                                    <form class="deleteForm" style="display:inline-block" action="{!!route('equipo.destroy', ['equipo' => $equipo->id])!!}" method="POST">
                                        <input name="_method" type="hidden" value="DELETE">
                                        {{ csrf_field() }}
                                        <button class="deleteBtn btn btn-danger btn-xs" type="submit" ><i class="fa fa-times fa-lg"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach<!-- endforeach partidos -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script>
        $('div.alert').not('.alert-important').delay(3000).slideUp(500);
        $('.deleteForm').on('click', '.deleteBtn', function(e){
            e.preventDefault();
            var $form=$(this.closest('form'));
            $('#confirm').modal({ keyboard: false })
                .on('click', '#delete-btn', function(){
                    console.log($form);
                    $form.submit();
            });
        });        
        $('.dataTable').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No se han encontrado registros.",
                "info": "P&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No hay datos para mostrar.",
                "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                "sSearch": "Buscar:",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                }
            }
        });

    </script>
@endsection
