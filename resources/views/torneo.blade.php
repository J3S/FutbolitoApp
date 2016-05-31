@extends('layouts.master')

@section('title', 'Torneo')

@section('contentHeaderTitle', 'Torneo')

@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li class="active">Torneo</li>
@endsection

@section('content')
    <div class="col-xs-12" style="padding-bottom: 15px;">
        <form>
            <button type="button" id="nuevoTorneoButton" class="btn btn-success" onclick="window.location='{{ route("torneo.create") }}'"><i class="fa fa-plus"></i> Nuevo Torneo</button>
        </form>
    </div>
    <div class="col-xs-12">
    {{--*/ $widget = 0 /*--}}
    @foreach($torneos as $torneo)
        @if($widget % 3 == 0)
            <div class="row">
        @endif
        <div class="col-md-4">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-blue">
                    <h3 class="widget-torneo">{!! $torneo['categoria'] !!}</h3>
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Equipos <span class="pull-right badge bg-blue">31</span></a></li>
                        <li><a href="#">Partidos <span class="pull-right badge bg-aqua">5</span></a></li>
                        <li><a href="#">Fecha de inicio <span class="pull-right badge bg-green">{!! $torneo['fechaInicio'] !!}</span></a></li>
                        <li><a href="#">Fecha de fin <span class="pull-right badge bg-red">{!! $torneo['fechaFin'] !!}</span></a></li>
                    </ul>
                    <div class="col-md-6">
                        <a href="{{ route('torneo.edit', $torneo->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                    </div>
                    <div class="col-md-6">
                        <form class="pull-right" action="/torneo/{{ $torneo->id }}" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-minus"></i> Desactivar</button>
                        </form>
                    </div>
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