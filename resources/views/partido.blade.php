@extends('layouts.master')

@section('title', 'Partido')

@section('contentHeaderTitle', 'Partido')

@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li class="active">Partido</li>
@endsection

@section('content')

    <div class="col-xs-12" style="padding-bottom: 15px;">
        <form>
            <button type="button" id="nuevoPartidoButton" class="btn btn-success" onclick="window.location='{{ route("partido.create") }}'"><i class="fa fa-plus"></i> Nuevo Partido</button>
        </form>
    </div>
	<div class="col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Buscar Partido</h3>
			</div><!-- /.box-header -->
            <form role="form" action="/selectPartido" method="post">
            {!! csrf_field() !!}
				<div class="box-body">
					<div class="form-group col-xs-12 col-sm-4">
						<label for="iniPartido">Fecha inicio</label>
						<input type="date" class="form-control" id="iniPartido" name="ini_partido">
					</div>
					<div class="form-group col-xs-12 col-sm-4">
						<label for="finPartido">Fecha fin</label>
						<input type="date" class="form-control" id="finPartido" name="fin_partido">
					</div>
					<div class="form-group col-xs-12 col-sm-4">
                        <label for="listaAnio">Torneo</label>
                        <select class="form-control input" id="torneo" name="torneo">
                        	<option selected="selected"></option>
                            @foreach($categorias as $categoria)
                                @foreach($torneos as $torneo)
                                    @if($categoria->id == $torneo->id_categoria)
                                        <option value="{{ $torneo['id'] }}">{{ $categoria['nombre'] }} {{ $torneo['anio'] }}</option>
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="inputJornada">Jornada #</label>
                            <input type="number" min="0" class="form-control" id="inputJornada" name="jornada" placeholder="Ingrese jornada del partido">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
                        <label for="equipo_local">Equipo local</label>
                        <select class="form-control" id="equipo_local" name="equipo_local">
                        	<option selected="selected"></option>
                            @foreach($equipos as $equipo)
                            	<option value="{{ $equipo['id'] }}">{{ $equipo['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
                        <label for="equipo_visitante">Equipo visitante</label>
                        <select class="form-control" id="equipo_visitante" name="equipo_visitante">
                        	<option selected="selected"></option>
                            @foreach($equipos as $equipo)
                            	<option value="{{ $equipo['id'] }}">{{ $equipo['nombre'] }}</option>
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
	@if(!empty($partidos))
		<div class="col-xs-12">
	    {{--*/ $widget = 0 /*--}}
	    @foreach($partidos as $partido)
	 		@foreach($torneos as $torneo)
	 			@if($partido->id_torneo == $torneo->id)
	 				{{--*/ $torneoPartido = $torneo /*--}}
	                @foreach($categorias as $categoria)
	                    @if($torneoPartido->id_categoria == $categoria->id)
	                        {{--*/ $categoriaTorneo = $categoria /*--}}
	                    @endif
	                @endforeach
				@endif
	 		@endforeach
	 		@foreach($equipos as $equipo)
	 			@if($partido->equipo_local == $equipo->nombre)
	 				{{--*/ $equipoLocal = $equipo /*--}}
				@endif
	 			@if($partido->equipo_visitante == $equipo->nombre)	
	 				{{--*/ $equipoVisit = $equipo /*--}}
				@endif
	 		@endforeach
	        @if($widget % 3 == 0)
	            <div class="row">
	        @endif
	        <div class="col-md-4">
	            <!-- Widget: user widget style 1 -->
	            <div class="box box-widget widget-user-2">
	                <!-- Add the bg color to the header using any of the bg-* classes -->
	                <div class="box-footer no-padding">
		                <ul class="nav nav-stacked">
		                	<li><h4 class="bg-green" style="text-align:center">{!! $categoriaTorneo['nombre'] !!} {!! $torneoPartido['anio'] !!} - Jornada # {!! $partido['jornada'] !!}</h4></li>
			                <li><a href="#">Resultado
								<span class="pull-right badge bg-green">{!! $equipoVisit['nombre'] !!}</span>
	                            <span class="pull-right badge bg-black">{!! $partido['gol_local'] !!} - {!! $partido['gol_visitante'] !!}</span>
	                            <span class="pull-right badge bg-green">{!! $equipoLocal['nombre'] !!}</span>
			                </a></li>
	                        <li><a href="#">Fecha <span class="pull-right badge bg-black">{!! $partido['fecha'] !!}</span></a></li>
	                        <li><a href="#">Lugar <span class="pull-right badge bg-black">{!! $partido['lugar'] !!}</span></a></li>
	                         <div class="col-md-6">
	                        <a href="{{ route('partido.edit', $partido->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Editar</a>
	                    </div>
	                    <div class="col-md-6">
	                        <form class="pull-right" action="/partido/{{ $partido->id }}" method="POST">
	                            <input type="hidden" name="_method" value="DELETE">
	                            {{ csrf_field() }}
	                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-minus"></i> Desactivar</button>
	                        </form>
	                    </div>
	                    </ul>
	                </div>

	            </div>
	        </div>
	        <!-- /.widget-user -->
	        @if($widget % 3 == 2)
	            </div>
	        @endif
	        <?php $widget++; ?>
	    @endforeach
	    </div>
    @endif
    <script>
	    // Carga dinámica de los equipos dependiendo del torneo seleccionado
	    document.getElementById("torneo").addEventListener("change", llenarEquiposSelect);

	    function llenarEquiposSelect() {
	        var equipos = <?php echo json_encode($equipos); ?>;
	        var torneos = <?php echo json_encode($torneos); ?>;
	        var torneoEquipos = <?php echo json_encode($torneoEquipos); ?>;
	        var torneo = $("#torneo option:selected").attr("value");
	        $('#equipo_local').find('option').remove().end();
	        $('#equipo_visitante').find('option').remove().end();
	        $('#equipo_local').append($('<option></option>'));
	        $('#equipo_visitante').append($('<option></option>'));
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
    </script>
@endsection

