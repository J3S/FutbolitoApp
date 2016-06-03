@extends('layouts.master')

@section('title', 'Partido')

@section('contentHeaderTitle', 'Partido')

@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('partido') }}"> Partido</a></li>
    <li class="active">Seleccionar partido</li>
@endsection

@section('content')
    <div class="col-xs-12">
    {{--*/ $widget = 0 /*--}}
    @foreach($partidos as $partido)
 		@foreach($torneos as $torneo)
 			@if($partido->id_torneo == $torneo->id)
 				{{--*/ $torneoPartido = $torneo /*--}}
			@endif
 		@endforeach
 		@foreach($equipos as $equipo)
 			@if($partido->id_equipoV == $equipo->id)
 				{{--*/ $equipoVisit = $equipo /*--}}
			@endif
 			@if($partido->id_equipo == $equipo->id)	
 				{{--*/ $equipoLocal = $equipo /*--}}
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
	                	<li><h3 class="widget-torneo bg-green" style="text-align:center">Torneo: {!! $torneoPartido['categoria'] !!}</h3></li>
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
@endsection