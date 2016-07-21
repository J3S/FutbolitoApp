<!-- 
    Vista principal de partido.
    Contiene el botón para crear un nuevo partido.
    Tiene opciones para buscar un partido por rango de fechas, torneo, jornada, equipo local y equipo visitante.
    Una vez realizada una busqueda, se muestran los partidos que cumplen con las condiciones ingresadas.
    Permite al usuario editar o desactivar los partidos que se muestran luego de realizar una busqueda.
-->
<!-- Plantilla -->
@extends('layouts.master')
<!-- Titulo -->
@section('title', 'Partido')
@section('contentHeaderTitle', 'Partido')

<!-- Elementos Breadcrumb -->
@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li class="active">Partido</li>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<style>
.alert {
	z-index: 99; 
	position: absolute; 
	left: 65%;
}

.panel-heading.accordion-toggle.collapsed:after {
    /* symbol for "collapsed" panels */
    content:"\e080";
    /* adjust as needed, taken from bootstrap.css */
}

.panel-heading.accordion-toggle:after {
    /* symbol for "opening" panels */
    font-family:'Glyphicons Halflings';
    /* essential for enabling glyphicon */
    content:"\e114";
    /* adjust as needed, taken from bootstrap.css */
    float: right;
    position: relative;
    bottom: 23px;
    font-size: 15pt;
    /* adjust as needed */
    color: grey;
    /* adjust as needed */
}

.panel-heading:hover {
    cursor: pointer;
}

.panel-heading:hover h4 {
    text-decoration: underline;
}
</style>
<!-- Contenido de la pagina -->
@section('content')
    <div class="col-xs-12">
    @include('flash::message')
        <form><!--  -->
        	<!-- Boton para crear un nuevo partido -->
            <button type="button" id="nuevoPartidoButton" class="btn btn-success" onclick="window.location='{{ route("partido.create") }}'"><i class="fa fa-plus"></i> Crear Partido</button>
        </form>
    <button type="button" id="buscarPartidoBtn" data-toggle="collapse" data-target="#boxBuscar" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar Partidos</button>
    </div>
	<div class="col-xs-12">
		<div class="box box-primary collapse" id="boxBuscar">
            <form role="form" action="/selectPartido" method="post">
            {!! csrf_field() !!}
				<div class="box-body">
                    <div class="col-xs-12 col-sm-2">
                        <div class="form-group">
                            <label for="anio">A&ntilde;o</label>
                            <!-- Campo para seleccionar el año para busqueda del partido -->
                            <input type="number" min="1970" max="2038" class="form-control" id="anio" name="anio">
                        </div>
                    </div>
					<div class="form-group col-xs-12 col-sm-2">
                        <label for="categoria">Categor&iacute;a</label>
                        <!-- Campo para seleccionar el torneo para busqueda del partido -->
                        <select class="form-control input" id="categoria" name="categoria">
                        	<option selected="selected"></option>
                            @foreach($categorias as $categoria)
                                <option>{{ $categoria['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
                        <label for="equipo_local">Equipo local</label>
                        <!-- Campo para seleccionar el equipo local para busqueda del partido -->
                        <select class="form-control" id="equipo_local" name="equipo_local">
                        	<option selected="selected"></option>
                            @foreach($equipos as $equipo)
                            	<option value="{{ $equipo['id'] }}">{{ $equipo['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
                        <label for="equipo_visitante">Equipo visitante</label>
                        <!-- Campo para seleccionar el equipo visitante para busqueda del partido -->
                        <select class="form-control" id="equipo_visitante" name="equipo_visitante">
                        	<option selected="selected"></option>
                            @foreach($equipos as $equipo)
                            	<option value="{{ $equipo['id'] }}">{{ $equipo['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-2">
                        <div class="form-group">
                            <label for="inputJornada">Jornada #</label>
                            <!-- Campo para seleccionar la jornada para busqueda del partido -->
                            <input type="number" min="0" class="form-control" id="inputJornada" name="jornada">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
						<label for="iniPartido">Fecha inicio</label>
						<!-- Campo para ingresar fecha inicial para busqueda del partido -->
						<input type="datetime-local" class="form-control" id="iniPartido" name="ini_partido">
					</div>
					<div class="form-group col-xs-12 col-sm-4">
						<label for="finPartido">Fecha fin</label>
						<!-- Campo para ingresar la fecha final para busqueda del partido -->
						<input type="datetime-local" class="form-control" id="finPartido" name="fin_partido">
					</div>
					<div class="col-xs-12" >
	                    <button type="submit" class="btn btn-success">Buscar</button>
	                </div>
				</div>
			</form>
		</div>
	</div>

	<!-- Seccion para mostrar los resultados de la busqueda de partidos -->
	<div class="col-xs-12">
	@include('modals.delete')
		<div class="panel-group" id="accordion">
		@if(!empty($partidos) and count($partidos) != 0)
			<h4 style="text-align:center;">Se encontraron {{ count($partidos) }} partidos.</h4>
			@foreach($torneos as $torneo)
				@foreach($categorias as $categoria)
					@if($torneo->id_categoria == $categoria->id)
						@if($torneo['estado'] == 1)
						<div class="panel panel-default">
	            	        <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#accordion" data-target="#expand{{ $torneo['id'] }}">
							     <h4 class="panel-title">{{ $categoria['nombre'] }} {{ $torneo['anio'] }}</h4>
							</div>
							<div id="expand{{ $torneo['id'] }}" class="collapse table-responsive">
								<div class="panel-body">
								<table class="dataTable table table-hover">
						            <thead>
						                <tr>
						                    <th>Jornada</th>
						                    <th>Fecha</th>
						                    <th>Lugar</th>
						                    <th>Resultado</th>
						                    <th>Editar</th>
						                    <th>Desactivar</th>
						                </tr>
						            </thead>  
						            <tbody>
									@foreach($partidos as $partido)
										@if($partido->id_torneo == $torneo->id)
										<tr>
											<td><span class="badge bg-black">{{ $partido['jornada'] }}</span></td>
											<td><span class="label label-default">{{ $partido['fecha'] }}</span></td>
											<td><span class="label label-default">{{ $partido['lugar'] }}</span></td>
											<td><span class="badge bg-green">{{ $partido['equipo_local'] }}</span> <span class="label label-default">{{ $partido['gol_local'] }} - {{ $partido['gol_visitante'] }}</span> <span class="badge bg-green">{{ $partido['equipo_visitante'] }}</span></td>
											<td>
												<a href="{{ route('partido.edit', $partido->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o fa-lg"></i></a>
											</td>
											<td>
												<form class="deleteForm" action="/partido/{{ $partido->id }}" method="POST">
					                            <input type="hidden" name="_method" value="DELETE">
					                            {{ csrf_field() }}

					                            <button type="submit" class="deleteBtn btn btn-danger btn-sm"><i class="fa fa fa-times fa-lg"></i></button>
					                        	</form>
				                        	</td>
										</tr>
										@endif<!-- endif partido.id = torneo.id -->
									@endforeach<!-- endforeach partidos -->
									</tbody>
								</table>
								</div>
							</div>
						</div>
						@endif
					@endif
				@endforeach
			@endforeach
	    @endif
		</div>
	</div>
    @if(!empty($partidos) and count($partidos) == 0)
    	<h4 class="text-center">La búsqueda no ha coincidido con ning&uacute;n partido.</h4>
    	<h5 class="text-center">Seleccione opciones m&aacute;s generales e intente de nuevo.</h4>
    @endif
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
	    // Carga dinámica de los equipos dependiendo del torneo (categoria y año) seleccionado.
	    document.getElementById("categoria").addEventListener("change", llenarEquiposSelect);
		document.getElementById("anio").addEventListener("change", llenarEquiposSelect);
		
	    // Funcion que filtra los equipos locales y visitantes dependiendo del torneo (categoria y año) seleccionado.
	    function llenarEquiposSelect() {
	        var equipos = <?php echo json_encode($equipos); ?>;
	        var torneoEquipos = <?php echo json_encode($torneoEquipos); ?>;
	        var torneos = <?php echo json_encode($torneos); ?>;
	        var categorias = <?php echo json_encode($categorias); ?>;
	        var categoria = $("#categoria option:selected").val();
	        var anio = $("#anio").val();
	        var categoriaID;
	        var torneo;

	        // Quito las opciones de equipo local/visitante y agrego opcion nula.
        	$('#equipo_local').find('option').remove().end();
	        $('#equipo_visitante').find('option').remove().end();
	        $('#equipo_local').append($('<option></option>'));
	        $('#equipo_visitante').append($('<option></option>'));

	        // Si usuario no ha seleccionado categoria, se cargan todos los equipos.
	        if(categoria == ""){
	        	for(var j = 0; j < equipos.length; ++j){
    				$('#equipo_local').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
					$('#equipo_visitante').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
        		}
	        }
	        // Si el usuario no he seleccionado un año, se cargan solo equipos de la categoria.
	        else if(anio == ""){
	        	for(var j = 0; j < equipos.length; ++j){
	        		if(equipos[j]['categoria'] == categoria){
	    				$('#equipo_local').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
						$('#equipo_visitante').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
	        		}
        		}	
	        }
	        // Si el usuario selecciono categoria y año, se cargan equipos del torneo con esa categoria y año.
	        else{
	        	for(var i = 0; i < categorias.length; ++i){
	        		if(categorias[i]['nombre'] == categoria){
	        			categoriaID = categorias[i]['id'];
	        		}
	        	}
	        	for(var i = 0; i < torneos.length; ++i){
	        		if(torneos[i]['id_categoria'] == categoriaID && torneos[i]['anio'] == anio){
	        			torneo = torneos[i]['id'];
	        		}
	        	}
		        // Agrego solo los equipos que participan en el torneo seleccionado.
		        for (var i = 0; i < torneoEquipos.length; ++i){
		        	if(torneoEquipos[i]['id_torneo'] == torneo){
		        		for(var j = 0; j < equipos.length; ++j){
		        			if(equipos[j]['id'] == torneoEquipos[i]['id_equipo']){
		        				$('#equipo_local').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
								$('#equipo_visitante').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
		        			}
		        		}
		        	}
	        	}
	        }
		}
    </script>
@endsection

